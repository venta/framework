<?php

use Venta\Contracts\Container\Container;
use Venta\Contracts\ExtensionProvider\CommandProvider;
use Venta\Contracts\ExtensionProvider\MiddlewareProvider;
use Venta\Contracts\ExtensionProvider\RouteProvider;
use Venta\Contracts\ExtensionProvider\ServiceProvider;
use Venta\Contracts\Routing\MiddlewareCollector as MiddlewareCollector;
use Venta\Contracts\Routing\RouteGroup;

class SampleExtension implements RouteProvider, MiddlewareProvider, ServiceProvider, CommandProvider
{
    /**
     * @inheritDoc
     */
    public function provideCommands(\Venta\Contracts\Console\CommandCollector $collector)
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
    public function provideRoutes(RouteGroup $collector)
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
