<?php declare(strict_types = 1);

namespace Venta\Mail;

use Exception;
use RuntimeException;
use Swift_DependencyContainer;
use Swift_Events_SimpleEventDispatcher;
use Swift_FileSpool;
use Swift_Mailer;
use Swift_MemorySpool;
use Swift_Message;
use Swift_SmtpTransport;
use Swift_Transport_MailTransport;
use Swift_Transport_NullTransport;
use Swift_Transport_SimpleMailInvoker;
use Swift_Transport_SpoolTransport;
use Venta\Contracts\Config\Config;
use Venta\Contracts\Event\EventDispatcher;
use Venta\Contracts\Mail\Mailer as MailerContract;
use Venta\Mail\Exception\TransportException;
use Venta\Mail\Exception\UnknownTransportException;

/**
 * Class Mailer
 *
 * @package Venta\Mail
 */
class Mailer implements MailerContract
{

    const SPOOL_SEND_EVENT = 'swiftmailer.spool.send';

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
     * Disabled flag
     *
     * @var bool
     */
    protected $disabled = false;

    /**
     * @var EventDispatcherAdapter
     */
    protected $eventDispatcherAdapter;

    /**
     * @var EventDispatcher
     */
    protected $eventDispatcher;

    /**
     * Default From:
     *
     * @var $from string
     */
    protected $from;

    /**
     * @var Swift_Transport_SpoolTransport
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
     * @var array
     */
    protected $transports = [];

    /**
     * Mailer constructor.
     *
     * @param \Venta\Contracts\Config\Config $config
     * @param EventDispatcher $eventDispatcher
     * @throws Exception
     */
    public function __construct(Config $config, EventDispatcher $eventDispatcher)
    {
        $this->getMailerFromGlobalConfig($config);
        $this->eventDispatcher = $eventDispatcher;
        $this->eventDispatcherAdapter = new EventDispatcherAdapter($eventDispatcher);
        $this->registerTransports();
    }

    /**
     * Get Swift_Message instance, setting default from if defined/not overwritten
     *
     * @return Swift_Message
     */
    public function getMessageBuilder(): Swift_Message
    {
        $messageInstance = Swift_Message::newInstance();
        $messageInstance->setFrom($this->from)
                        ->setTo($this->to);

        return $messageInstance;
    }

    /**
     * @return Swift_Transport_SpoolTransport
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
     * @return Swift_Mailer
     * @throws TransportException
     */
    public function spoolWithTransport($transport = '')
    {
        $spool = $this->spoolTransport;
        if ($spool === null) {
            throw new TransportException('Mailer spool is not configured.');
        }
        $spoolRealTransport = $this->getTransport($transport);

        $this->eventDispatcher->attach(self::SPOOL_SEND_EVENT, 'send.swiftmailer.spooled',
            function () use ($spoolRealTransport, $spool) {
                $failedRecipients = [];
                $spool->getSpool()->flushQueue($spoolRealTransport(), $failedRecipients);

                return $failedRecipients;
            });

        return new Swift_Mailer($this->spoolTransport);
    }

    /**
     * Get Swift_Mailer with Swift_Transport
     *
     * @param string $transportName
     * @return Swift_Mailer
     */
    public function withTransport(string $transportName = ''): Swift_Mailer
    {
        $transport = $this->getTransport($transportName);

        return new Swift_Mailer($transport());
    }

    /**
     * Parse config file interpreting settings
     *
     * @param \Venta\Contracts\Config\Config $config
     * @throws RuntimeException|Exception
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
     * @param \Venta\Contracts\Config\Config $config
     */
    protected function getMailerFromGlobalConfig(Config $config)
    {
        if (!$config->has('mailer')) {
            throw new Exception('Mailer config was not found.');
        }
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
        if ($transportName === '' || $transportName === 'default') {
            return $this->transports[$this->defaultTransport];
        }

        if (!array_key_exists($transportName, $this->transports)) {
            throw new UnknownTransportException(
                sprintf('Transport "%s" was not configured.', $transportName)
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
     * @throws Exception
     */
    protected function prepareTransportFactory($transport, Config $config)
    {
        $eventDispatcherAdapter = $this->eventDispatcherAdapter;
        switch ($transport) {
            case('smtp'):
                $closure = function () use ($config) {
                    $transportInstance = new Swift_SmtpTransport(
                        $config->get('host'),
                        $config->get('port'),
                        $config->get('encryption')
                    );
                    $dependencies = Swift_DependencyContainer::getInstance()->createDependenciesFor('transport.smtp');
                    foreach ($dependencies as &$dependency) {
                        if ($dependency instanceof Swift_Events_SimpleEventDispatcher) {
                            $dependency = $this->eventDispatcherAdapter;
                            unset($dependency);
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
                $closure = function () use ($eventDispatcherAdapter) {
                    return new Swift_Transport_MailTransport(
                        new Swift_Transport_SimpleMailInvoker(),
                        $eventDispatcherAdapter
                    );
                };
                break;
            case('null'):
                $closure = function () use ($eventDispatcherAdapter) {
                    return new Swift_Transport_NullTransport($eventDispatcherAdapter);
                };
                break;
            default:
                throw new UnknownTransportException(
                    sprintf('Unknown transport: "%s" defined in "%s" section.',
                        $transport,
                        $config->getName())
                );
                break;
        }

        return $closure;
    }

    /**
     * Register transport factories
     *
     * @throws Exception
     * @return array
     */
    protected function registerTransports()
    {
        $deliveryIsDisabled = $this->configs->get('disable_delivery', false);
        if ($deliveryIsDisabled === true || $deliveryIsDisabled === 'true') {
            $this->defaultTransport = 'null';
            $this->transports['null'] = $this->prepareTransportFactory('null', new \Venta\Config\Config());
            $this->disabled = true;

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
            throw new TransportException('At least one Mailer transport must be defined.');
        }
        $this->defaultTransport = $this->configs->get('default', key($this->transports));
        if ($this->isSpoolEnabled()) {
            $this->spoolTransport = $this->setUpSpoolTransport();
        }

        return $this->transports;
    }

    /**
     * @return Swift_Transport_SpoolTransport|null
     * @throws UnknownTransportException|TransportException|\Swift_IoException
     */
    protected function setUpSpoolTransport()
    {
        $spool = $this->configs->get('spool', false);
        if ($spool && $spool instanceof Config) {
            $spoolInstance = null;
            switch ($spool->get('type')) {
                case 'memory':
                    $spoolInstance = new Swift_MemorySpool();
                    break;
                case 'file':
                    if (!$spool->get('path', false)) {
                        throw new TransportException('Path must be provided to use File type Spool.');
                    }
                    $spoolInstance = new Swift_FileSpool($spool->get('path'));
                    break;
                default:
                    throw new UnknownTransportException(
                        sprintf('Unknown spool type "%s" defined in "%s" section.',
                            $spool->get('type'),
                            $this->configs->getName()
                        )
                    );
            }

            if ($spoolInstance !== null) {
                $eventDispatcherAdapter = $this->eventDispatcherAdapter;

                return new Swift_Transport_SpoolTransport($eventDispatcherAdapter, $spoolInstance);
            }
        }

        return null;
    }

    /**
     * @param \Venta\Contracts\Config\Config $config
     * @throws TransportException
     * @throws UnknownTransportException
     */
    protected function validateTransportSettings(Config $config)
    {
        if (!$config->has('transport')) {
            throw new TransportException(sprintf('Transport was not defined for "%s" section.', $config->getName()));
        }
        $transport = $config->get('transport');
        switch ($transport) {
            case('smtp'):
                if (!$config->has('host')) {
                    throw new TransportException(
                        sprintf('Host must be provided to use SMTP protocol in "%s" section.', $config->getName())
                    );
                }
                break;
            case('gmail'):
                if (!$config->has('username') || !$config->has('password')) {
                    throw new TransportException(
                        sprintf('Username and password must be provided to use gmail SMTP defined in "%s" section.',
                            $config->getName())
                    );
                }
                break;
        }
    }
}