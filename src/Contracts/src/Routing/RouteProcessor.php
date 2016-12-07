<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface RouteProcessor
 *
 * @package Venta\Contracts\Routing
 */
interface RouteProcessor
{

    /**
     * Processes route path.
     *
     * @param Route $route
     * @return Route
     */
    public function process(Route $route): Route;

}