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