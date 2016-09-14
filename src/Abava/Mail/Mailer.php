<?php declare(strict_types = 1);
/**
 * Class Mailer
 *
 * @package Abava\Mail
 */
namespace Abava\Mail;

use Abava\Config\Contract\Config;
use Abava\Event\Contract\EventManager;
use Abava\Mail\Contract\Mailer as MailerContract;
use Abava\Mail\Exception\TransportException;
use Abava\Mail\Exception\UnknownTransportException;

/**
 * Class Mailer
 *
 * @package Abava\Mail
 */
class Mailer implements MailerContract
{

    const SPOOL_SEND_EVENT_NAME = 'swiftmailer.spool.send';

    /**
     * @var $configs Config
     */
    public $configs;

    /**
     * Stores default transport name
     *
     * @var $defaultTransport string
     */
    protected $defaultTransport;

    /**
     * Is sending email disabled in config
     *
     * @var $disabled bool
     */
    protected $disabled = false;

    /**
     * @var EventDispatcherAdapter
     */
    protected $eventDispatcherAdapter;

    /**
     * @var EventManager
     */
    protected $eventManager;

    /**
     * Default From:
     *
     * @var $from string
     */
    protected $from;

    /**
     * @var \Swift_Transport_SpoolTransport
     */
    protected $spoolTransport;

    /**
     * Default To:
     *
     * @var $to string
     */
    protected $to;

    /**
     * Registered transport storage
     *
     * @throws \Exception
     * @var $transports array
     */
    protected $transports = [];

    /**
     * Mailer constructor.
     *
     * @param Config $config
     * @param EventManager $eventManager
     * @throws \Exception
     */
    public function __construct(Config $config, EventManager $eventManager)
    {
        $this->eventManager = $eventManager;
        $this->eventDispatcherAdapter = new EventDispatcherAdapter($eventManager);
        $this->getDefaultConfig($config);
        $this->registerTransports();
    }

    /**
     * Get Swift_Message instance, setting default from if defined/not overwritten
     *
     * @return \Swift_Message
     */
    public function getMessageBuilder(): \Swift_Message
    {
        $messageInstance = \Swift_Message::newInstance();
        $messageInstance->setFrom($this->from)
                        ->setTo($this->to);

        return $messageInstance;
    }

    /**
     * @return \Swift_Transport_SpoolTransport
     */
    public function getSpoolTransport()
    {
        return $this->spoolTransport;
    }

    /**
     * @return bool
     */
    public function isDisabled() : bool
    {
        return $this->disabled;
    }

    /**
     * @return mixed
     */
    public function isSpoolEnabled()
    {
        return $this->configs->has('spool');
    }

    /**
     * @param string $transport
     * @return \Swift_Mailer
     * @throws TransportException
     */
    public function spoolWithTransport($transport = '')
    {
        $spool = $this->spoolTransport;
        if ($spool === null) {
            throw new TransportException('Spool transport was not defined');
        }
        $spoolRealTransport = $this->getTransport($transport);
        $this->eventManager->attach(self::SPOOL_SEND_EVENT_NAME, 'send.swiftmailer.spooled',
            function () use ($spoolRealTransport, $spool) {
                $failedRecipients = [];
                $spool->getSpool()->flushQueue($spoolRealTransport(), $failedRecipients);

                return $failedRecipients;
            });

        return new \Swift_Mailer($this->spoolTransport);
    }

    /**
     * Get Swift_Mailer with Swift_Transport
     *
     * @param string $transportName
     * @return \Swift_Mailer
     */
    public function withTransport(string $transportName = ''): \Swift_Mailer
    {
        $transport = $this->getTransport($transportName);

        return new \Swift_Mailer($transport());
    }

    /**
     * Parse config file interpreting settings
     *
     * @param Config $config
     * @throws \RuntimeException|\Exception
     * @return \Closure
     */
    protected function configureTransport(Config $config)
    {
        $this->validateTransportSettings($config);
        $transport = $config->get('transport');
        if ($transport === 'gmail') {
            $config->set('encryption', 'ssl');
            $config->set('auth_mode', 'login');
            $config->set('host', 'smtp.gmail.com');
            $transport = 'smtp';
        }

        if (!$config->get('port')) {
            $config->set('port', $config->get('encryption', false) ? 465 : 25);
        }

        return $this->prepareTransportFactory($transport, $config);
    }

    /**
     * Get mailer configs
     *
     * @param Config $config
     */
    protected function getDefaultConfig(Config $config)
    {
        $this->configs = clone $config->get('mailer');
        $this->to = ($this->configs->get('to') instanceof Config)
            ? $this->configs->get('to')->toArray()
            : $this->configs->get('to');
        $this->from = ($this->configs->get('from') instanceof Config)
            ? $this->configs->get('from')->toArray()
            : $this->configs->get('from');

    }

    /**
     * Returns proper transport closure factory
     *
     * @param $transportName
     * @return \Closure
     */
    protected function getTransport($transportName)
    {
        if ($transportName === '') {
            return $this->transports[$this->defaultTransport];
        }

        if (!array_key_exists($transportName, $this->transports)) {
            throw new UnknownTransportException(
                sprintf('Transport "%s" was not configured', $transportName)
            );
        }

        return $this->transports[$transportName];
    }

    /**
     * Wrap transport instantiation into closure passing necessary config params
     *
     * @param $transport
     * @param $config Config
     * @return \Closure
     * @throws \Exception
     */
    protected function prepareTransportFactory($transport, Config $config)
    {
        $eventManagerAdapter = $this->eventDispatcherAdapter;
        switch ($transport) {
            case('smtp'):
                $closure = function () use ($config) {
                    $transportInstance = new \Swift_SmtpTransport(
                        $config->get('host'),
                        $config->get('port'),
                        $config->get('encryption')
                    );
                    $dependencies = \Swift_DependencyContainer::getInstance()->createDependenciesFor('transport.smtp');
                    foreach ($dependencies as &$dependency) {
                        if ($dependency instanceof \Swift_Events_SimpleEventDispatcher) {
                            $dependency = $this->eventDispatcherAdapter;
                        }
                    }
                    call_user_func_array([$transportInstance, 'Swift_Transport_EsmtpTransport::__construct'],
                        $dependencies);

                    if ($config->has('auth_mode')) {
                        $transportInstance->setAuthMode($config->get('auth_mode'));
                    }
                    if ($config->has('username')) {
                        $transportInstance->setUsername($config->get('username'));
                    }
                    if ($config->has('password')) {
                        $transportInstance->setPassword($config->get('password'));
                    }

                    return $transportInstance;
                };
                break;
            case('mail'):
                $closure = function () use ($eventManagerAdapter) {
                    return new \Swift_Transport_MailTransport(
                        new \Swift_Transport_SimpleMailInvoker(),
                        $eventManagerAdapter
                    );
                };
                break;
            case('null'):
                $closure = function () use ($eventManagerAdapter) {
                    return new \Swift_Transport_NullTransport($eventManagerAdapter);
                };
                break;
            default:
                throw new Exception\UnknownTransportException('Unknown transport type');
                break;
        }

        return $closure;
    }

    /**
     * Register transport factories
     *
     * @throws \Exception
     * @return array
     */
    protected function registerTransports()
    {
        if ($this->configs->get('disable_delivery', false)) {
            $this->defaultTransport = 'null';
            $this->transports['null'] = $this->prepareTransportFactory('null', new \Abava\Config\Config());

            return $this->transports;
        }

        if ($this->configs->get('transport')) {
            $this->transports['default'] = $this->configureTransport($this->configs);
        } else {
            foreach ($this->configs as $name => $config) {
                if ($config instanceof Config && !in_array($name, ['from', 'to', 'spool'], true)) {
                    $this->transports[$name] = $this->configureTransport($config);
                }
            }
        }

        if (count($this->transports) === 0) {
            throw new TransportException('No mail transport defined');
        }

        $this->defaultTransport = $this->configs->has('default', false)
            ? $this->configs->get('default')
            : key($this->transports);


        if ($this->isSpoolEnabled()) {
            $this->spoolTransport = $this->setUpSpoolTransport();
        }

        return $this->transports;
    }

    /**
     * @return \Swift_Transport_SpoolTransport|null
     * @throws UnknownTransportException|TransportException|\Swift_IoException
     */
    protected function setUpSpoolTransport()
    {
        $spool = $this->configs->get('spool', false);
        if ($spool && $spool instanceof Config) {
            $spoolInstance = null;
            switch ($spool->get('type')) {
                case 'memory':
                    $spoolInstance = new \Swift_MemorySpool();
                    break;
                case 'file':
                    if (!$spool->get('path', false)) {
                        throw new TransportException('Filesystem spool must provide a path');
                    }
                    $spoolInstance = new \Swift_FileSpool($spool->get('path'));
                    break;
                default:
                    throw new UnknownTransportException(sprintf('Unknown spool type "%s".', $spool->get('type')));
            }

            if ($spoolInstance !== null) {
                $eventManagerAdapter = $this->eventDispatcherAdapter;

                return new \Swift_Transport_SpoolTransport($eventManagerAdapter, $spoolInstance);
            }
        }

        return null;
    }

    /**
     * @param Config $config
     * @throws TransportException
     * @throws UnknownTransportException
     */
    protected function validateTransportSettings(Config $config)
    {
        if (!$config->has('transport')) {
            throw new TransportException('Transport was not defined');
        }
        $transport = $config->get('transport');
        switch ($transport) {
            case('smtp'):
                if (!$config->has('host')) {
                    throw new TransportException('Host must be provided for SMTP protocol');
                }
                break;
            case('gmail'):
                if (!$config->has('username') || !$config->has('password')) {
                    throw new TransportException('Username and password must be provided to use gmail SMTP');
                }
                break;
            case('mail'):
                break;
            default:
                throw new UnknownTransportException('Unknown transport type');
                break;
        }
    }
}