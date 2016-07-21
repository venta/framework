<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Abava\Routing\Contract\Middleware\Collector;

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
     * @param Collector $collector
     * @return void
     */
    public function middlewares(Collector $collector);

}