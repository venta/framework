<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Abava\Routing\MiddlewareCollector;

/**
 * Interface Middlewares
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface Middlewares
{

    /**
     * Add extension middlewares using middleware collector
     *
     * @param MiddlewareCollector $middlewareCollector
     * @return void
     */
    public function middlewares(MiddlewareCollector $middlewareCollector);

}