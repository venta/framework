<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Contracts\Routing\RouteGroup;

/**
 * Interface RouteProvider
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface RouteProvider
{

    /**
     * Add extension routes using routes collector
     *
     * @param \Venta\Contracts\Routing\RouteGroup $collector
     * @return void
     */
    public function provideRoutes(RouteGroup $collector);

}