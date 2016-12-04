<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface MutableRouteCollection
 *
 * @package Venta\Contracts\Routing
 */
interface MutableRouteCollection extends RouteCollection
{
    /**
     * Adds route group.
     *
     * @param RouteGroup $group
     * @return MutableRouteCollection
     */
    public function addGroup(RouteGroup $group): MutableRouteCollection;

    /**
     * Adds route.
     *
     * @param Route $route
     * @return MutableRouteCollection
     */
    public function addRoute(Route $route): MutableRouteCollection;

}