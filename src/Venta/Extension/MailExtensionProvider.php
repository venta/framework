<?php declare(strict_types = 1);

namespace Venta\Extension;

use Abava\Config\Config;
use Venta\Contract\ExtensionProvider\ConfigProvider;
use Venta\Contract\ExtensionProvider\ServiceProvider;
use Venta\Contract\ExtensionProvider\CommandProvider;
use Abava\Console\Contract\Collector as CollectorContract;
use Abava\Container\Contract\Container as ContainerContract;
use Abava\Config\Contract\Factory as FactoryContract;
use Abava\Config\Contract\Config as ConfigContract;
use Abava\Mail\Contract\Mailer as MailerContract;
use Venta\Contract\Kernel;

/**
 * Class MailExtensionProvider  */
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
        $collector->addCommand(\Venta\Commands\Mail::class);
    }

    /**
     * @inheritdoc
     */
    public function provideConfig(FactoryContract $factory): ConfigContract
    {
        $mailConfig = [
            'some' => 'value',
            'mailer' => [
                'spool' => [
                    'type' => 'file',
                    'path' => 'storage/spool',
                ],
                'mail' => [
                    'transport' => 'mail',
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
        $container->share(MailerContract::class, \Abava\Mail\Mailer::class, ['mailer']);
    }
}