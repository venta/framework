<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Routing\Contract\Middleware\Collector;

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
     * @param Collector $collector
     * @return void
     */
    public function provideMiddlewares(Collector $collector);

}