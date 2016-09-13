<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Abava\Routing\Contract\Middleware\Collector;

/**
 * Interface MiddlewareProvider
 *
 * @package Venta\Contract\ExtensionProvider
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