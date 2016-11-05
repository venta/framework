<?php declare(strict_types = 1);
namespace Venta\Mail;

use Venta\Contracts\Config\Config;
use Venta\Contracts\Event\EventManager;
use Venta\Contracts\Mail\TransportFactory as TransportFactoryContract;
use Swift_Transport;
use Swift_DependencyContainer;
use Swift_Events_SimpleEventDispatcher;
use Swift_SmtpTransport;
use Swift_Transport_MailTransport;
use Swift_Transport_SimpleMailInvoker;
use Swift_Transport_NullTransport;
use Venta\Mail\Exception\UnknownTransportException;
use Swift_FileSpool;
use Swift_MemorySpool;
use Swift_Transport_SpoolTransport;
use Swift_Spool;

/**
 * Class TransportFactory
 *
 * @package Venta\Mail
 */
class TransportFactory implements TransportFactoryContract
{
    protected $eventManager;

    protected $eventManagerAdapter;

    protected $mailerConfig;

    protected $transport;

    /**
     * TransportFactory constructor.
     *
     * @param EventManager $eventManager
     * @param Config $globalConfig
     */
    public function __construct(EventManager $eventManager, Config $globalConfig)
    {
        $this->eventManager = $eventManager;
        $this->eventManagerAdapter = new EventManagerAdapter($eventManager);
        $this->mailerConfig = $this->parseConfig($globalConfig);
    }

    /** @inheritdoc */
    public function getTransport(): Swift_Transport
    {
        if ($this->transport !== null) {
            return $this->transport;
        }

        $transport = $this->decideOnTransport();
        if (!$this->mailerConfig->has('spool')) {
            return $this->transport = $transport;
        }

        /** @var $spoolTransport \Swift_FileSpool|\Swift_MemorySpool */
        $spool = $this->getSpoolTransport($this->mailerConfig);
        $spoolTransport = new Swift_Transport_SpoolTransport($this->eventManagerAdapter, $spool);

        $this->eventManager->attach(Mailer::SPOOL_SEND_EVENT, 'send.swiftmailer.spooled', function() use ($spoolTransport, $transport){
            $failedRecipients = [];
            $spoolTransport->getSpool()->flushQueue($transport, $failedRecipients);

            return $failedRecipients;
        });

        return $this->transport = $spoolTransport;
    }

    /**
     * @return Swift_Transport
     * @throws UnknownTransportException
     */
    protected function decideOnTransport()
    {
        if ($this->mailerConfig->get('disable_delivery')){
            $transport = 'null';
        } else {
            $config = $this->retrieveTransportConfig();
            $transport = $config->get('transport');
        }

        switch ($transport) {
            case 'smtp':
                 return $this->getSmtpTransport($config);
            case 'mail':
                 return $this->getMailTransport();
            case 'null':
                 return $this->getNullTransport();
            default:
                throw new UnknownTransportException(
                    sprintf('Unknown transport: "%s" defined in "%s" section.',
                        $transport,
                        $config->getName())
                );
        }
    }

    /**
     * @return Swift_Transport
     */
    protected function getMailTransport(): Swift_Transport
    {
        return new Swift_Transport_MailTransport(
            new Swift_Transport_SimpleMailInvoker(),
            $this->eventManagerAdapter
        );
    }

    /**
     * @return Swift_Transport
     */
    protected function getNullTransport(): Swift_Transport
    {
        return new Swift_Transport_NullTransport($this->eventManagerAdapter);
    }

    /**
     * @param Config $config
     * @return Swift_Transport
     */
    protected function getSmtpTransport(Config $config): Swift_Transport
    {
        $transportInstance = new Swift_SmtpTransport(
            $config->get('host'),
            $config->get('port'),
            $config->get('encryption')
        );
        $dependencies = Swift_DependencyContainer::getInstance()->createDependenciesFor('transport.smtp');
        foreach ($dependencies as &$dependency) {
            if ($dependency instanceof Swift_Events_SimpleEventDispatcher) {
                $dependency = $this->eventManagerAdapter;
            }
        }
        unset($dependency);
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
    }

    /**
     * @param Config $config
     * @return Swift_Transport
     */
    protected function getSpoolTransport(Config $config): Swift_Spool
    {
        $type = $config->get('spool')->get('type');
        if ($type === 'file' && $config->get('spool')->get('path')) {
            return new Swift_FileSpool($config->get('spool')->get('path'));
        }

        return new Swift_MemorySpool;
    }

    /**
     * @param Config $config
     * @return Config
     * @throws \Exception
     */
    protected function parseConfig(Config $config): Config
    {
        if (!$config->has('mailer')) {
            throw new \Exception('Mailer config is mandatory');
        }

        return $config->get('mailer');
    }

    /**
     * @return mixed
     */
    protected function retrieveTransportConfig()
    {
        if ($this->mailerConfig->has('transport')) {

            return $this->mailerConfig;
        }
        $defaultTransportName = $this->mailerConfig->get('default');

        return $this->mailerConfig->get($defaultTransportName);
    }
}