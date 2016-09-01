<?php

use Abava\Container\Contract\Container;
use Abava\Routing\Contract\Group;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollector;
use Venta\Contract\ExtensionProvider\Bindings;
use Venta\Contract\ExtensionProvider\Commands;
use Venta\Contract\ExtensionProvider\Middlewares;
use Venta\Contract\ExtensionProvider\Routes;

class SampleExtension implements Routes, Middlewares, Bindings, Commands
{
    /**
     * @inheritDoc
     */
    public function bindings(Container $container)
    {
    }

    /**
     * @inheritDoc
     */
    public function commands(\Abava\Console\Contract\Collector $collector)
    {
    }

    /**
     * @inheritDoc
     */
    public function middlewares(MiddlewareCollector $collector)
    {
    }

    /**
     * @inheritDoc
     */
    public function routes(Group $collector)
    {
    }
}

return [
    SampleExtension::class,
];
