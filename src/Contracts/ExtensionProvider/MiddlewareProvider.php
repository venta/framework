<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Contracts\Routing\MiddlewareCollector;

/**
 * Interface MiddlewareProvider
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface MiddlewareProvider
{

    /**
     * Add extension middlewares using middleware collector
     *
     * @param MiddlewareCollector $collector
     * @return void
     */
    public function provideMiddlewares(MiddlewareCollector $collector);

}