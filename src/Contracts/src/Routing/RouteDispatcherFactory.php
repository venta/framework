<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface RouteDispatcherFactory
 *
 * @package Venta\Routing
 */
interface RouteDispatcherFactory
{

    /**
     * Returns route handler dispatching delegate.
     *
     * @param Route $route
     * @return Delegate
     */
    public function create(Route $route): Delegate;

}