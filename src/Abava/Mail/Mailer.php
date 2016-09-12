<?php declare(strict_types = 1);
/**
 * Class Mailer
 *
 * @package Abava\Mail
 */
namespace Abava\Mail;

use Abava\Config\Contract\Config;
use Abava\Mail\Contract\Mailer as MailerContract;
use Abava\Mail\Exception\UnknownTransportException;

/*
 * Available configuration fields:
 *
 * transport [smtp|mail|sendmail|gmail]
 * username
 * password
 * host
 * port
 * encryption [tls|ssl]
 * auth_mode[plain|login|cram-md5]
 * spool [?]
 *  * type
 *  * path
 * to
 * from
 * disable_delivery
 */

/**
 * Class Mailer
 *
 * @package Abava\Mail
 */
class Mailer implements MailerContract
{
    /**
     * @var $configs Config
     */
    public $configs;

    /**
     * @var $defaultAddress
     */
    protected $defaultAddress;

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
     * Default from
     *
     * @var $from string
     */
    protected $from;

    /**
     * Default to
     *
     * @var $to string
     */
    protected $to;

    /**
     * Registered transport storage
     *
     * @var $transports array
     */
    protected $transports = [];

    /**
     * Mailer constructor.
     */
    public function __construct(Config $config)
    {
        $this->getMailerConfig($config);
        $this->registerTransportFactories();
    }

    /**
     * Get Swif_Message instance, setting default from if defined/not overwritten
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
     * @return bool
     */
    public function isDisabled() : bool
    {
        return $this->disabled;
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
     * @param array $config
     * @return \Closure
     */
    protected function configureTransport(Config $config)
    {
        if (!$config->has('transport')) {
            $transport = 'null';
        } elseif ($config->get('transport') === 'gmail') {
            $config->set('encryption', 'ssl');
            $config->set('auth_mode', 'login');
            $config->set('host', 'smtp.gmail.com');
            $transport = 'smtp';
        } else {
            $transport = $config->get('transport');
        }
        $config->set('port', $config->get('encryption', false) ? 465 : 25);

        return $this->prepareTransportFactory($transport, $config);
    }

    /**
     * Get mailer configs
     *
     * @param Config $config
     */
    protected function getMailerConfig(Config $config)
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
        if ($transportName === '' || !array_key_exists($transportName, $this->transports)) {
            return $this->transports[$this->defaultTransport];
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
        switch ($transport) {
            //smtp transport
            case('smtp'):
                $closure = function () use ($config) {
                    $transportInstance = \Swift_SmtpTransport::newInstance(
                        $config->get('host'),
                        $config->get('port'),
                        $config->get('encryption')
                    );

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
            //PHP mail() transport
            case('mail'):
                $closure = function () {
                    return \Swift_MailTransport::newInstance();
                };
                break;
            //Swift_NullTransport
            case('null'):
                $closure = function () {
                    return \Swift_NullTransport::newInstance();
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
     * @return array
     */
    protected function registerTransportFactories()
    {
        if ($this->configs->get('disable_delivery', false)) {
            $this->defaultTransport = 'null';
            $this->transports['null'] = $this->prepareTransportFactory('null', new \Abava\Config\Config());

            return $this->transports;
        }

        foreach ($this->configs as $name => $config) {
            if ($config instanceof Config && !in_array($name, ['from', 'to'], true)) {
                $this->transports[$name] = $this->configureTransport($config);
            }
        }

        $this->defaultTransport = $this->configs->has('default')
            ? $this->configs->get('default')
            : key($transports);

        return $this->transports;
    }
}