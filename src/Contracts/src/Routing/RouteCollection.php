<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface RouteCollection
 *
 * @package Venta\Contracts\Routing
 */
interface RouteCollection
{

    /**
     * Adds route group.
     *
     * @param RouteGroup $group
     * @return RouteCollection
     */
    public function addGroup(RouteGroup $group): RouteCollection;

    /**
     * Adds route.
     *
     * @param Route $route
     * @return RouteCollection
     */
    public function addRoute(Route $route): RouteCollection;

    /**
     * Returns all routes.
     *
     * @return Route[]
     */
    public function getRoutes(): array;

}