<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Abava\Routing\Contract\Middleware\Collector;

/**
 * Interface Middlewares
 *
 * @package Venta\Contract\ExtensionProvider
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