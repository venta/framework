<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface ImmutableRouteCollection
 *
 * @package Venta\Contracts\Routing
 */
interface ImmutableRouteCollection
{
    /**
     * Finds a route by name.
     *
     * @param string $routeName
     * @return null|Route
     */
    public function findByName(string $routeName);

    /**
     * Returns all routes.
     *
     * @return Route[]
     */
    public function getRoutes(): array;

}