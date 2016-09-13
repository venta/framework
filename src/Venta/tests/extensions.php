<?php

use Abava\Container\Contract\Container;
use Abava\Routing\Contract\Group;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollector;
use Venta\Contract\ExtensionProvider\CommandProvider;
use Venta\Contract\ExtensionProvider\MiddlewareProvider;
use Venta\Contract\ExtensionProvider\RouteProvider;
use Venta\Contract\ExtensionProvider\ServiceProvider;

class SampleExtension implements RouteProvider, MiddlewareProvider, ServiceProvider, CommandProvider
{
    /**
     * @inheritDoc
     */
    public function provideCommands(\Abava\Console\Contract\Collector $collector)
    {
    }

    /**
     * @inheritDoc
     */
    public function provideMiddlewares(MiddlewareCollector $collector)
    {
    }

    /**
     * @inheritDoc
     */
    public function provideRoutes(Group $collector)
    {
    }

    /**
     * @inheritDoc
     */
    public function setServices(Container $container)
    {
    }
}

return [
    SampleExtension::class,
];
