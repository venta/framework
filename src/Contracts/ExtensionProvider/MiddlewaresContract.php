<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Abava\Routing\MiddlewareCollector;

/**
 * Interface MiddlewaresContract
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface MiddlewaresContract
{

    /**
     * Add extension middlewares using middleware collector
     *
     * @param MiddlewareCollector $middlewareCollector
     * @return void
     */
    public function middlewares(MiddlewareCollector $middlewareCollector);

}