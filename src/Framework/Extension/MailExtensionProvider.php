<?php declare(strict_types = 1);

namespace Venta\Framework\Extension;

use Venta\Config\Config;
use Venta\Contracts\Config\Config as ConfigContract;
use Venta\Contracts\Config\ConfigFactory as FactoryContract;
use Venta\Contracts\Console\CommandCollector as CollectorContract;
use Venta\Contracts\Container\Container as ContainerContract;
use Venta\Contracts\ExtensionProvider\CommandProvider;
use Venta\Contracts\ExtensionProvider\ConfigProvider;
use Venta\Contracts\ExtensionProvider\ServiceProvider;
use Venta\Contracts\Mail\Mailer as MailerContract;
use Venta\Mail\Mailer;
use Venta\Contracts\Mail\TransportFactory as TransportFactoryContract;
use Venta\Mail\TransportFactory;
use Swift_Mailer;

/**
 * Class MailExtensionProvider
 */
class MailExtensionProvider implements
    ServiceProvider,
    CommandProvider,
    ConfigProvider
{
    /**
     * @inheritdoc
     */
    public function provideCommands(CollectorContract $collector)
    {
        $collector->addCommand(\Venta\Framework\Commands\Mail::class);
    }

    /**
     * @inheritdoc
     */
    public function provideConfig(FactoryContract $factory): ConfigContract
    {
        $mailConfig = [
            'mailer' => [
                'localhost' => [
                    'transport' => 'smtp',
                    'host' => 'localhost',
                    'port' => '1234',
                    'username' => 'user',
                ],
            ],
        ];

        return new Config($mailConfig);
    }

    /**
     * @inheritdoc
     */
    public function setServices(ContainerContract $container)
    {
        $container->share(TransportFactoryContract::class, TransportFactory::class);
        $container->set(Swift_Mailer::class, function (TransportFactoryContract $factory) {
            return new Swift_Mailer($factory->getTransport());
        });
        $container->share(MailerContract::class, Mailer::class, ['mailer']);
    }
}