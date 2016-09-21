<?php

use Venta\Container\Contract\Container;
use Venta\Routing\Contract\Group;
use Venta\Routing\Contract\Middleware\Collector as MiddlewareCollector;
use Venta\Contracts\ExtensionProvider\CommandProvider;
use Venta\Contracts\ExtensionProvider\MiddlewareProvider;
use Venta\Contracts\ExtensionProvider\RouteProvider;
use Venta\Contracts\ExtensionProvider\ServiceProvider;

class SampleExtension implements RouteProvider, MiddlewareProvider, ServiceProvider, CommandProvider
{
    /**
     * @inheritDoc
     */
    public function provideCommands(\Venta\Console\Contract\Collector $collector)
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
