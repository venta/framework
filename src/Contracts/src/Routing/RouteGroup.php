<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface RouteGroup
 *
 * @package Venta\Contracts\Routing
 */
interface RouteGroup extends RouteCollection
{

    /**
     * Calls callback to collect routes passing new group instance.
     *
     * @param callable $callback
     * @return RouteGroup
     */
    public static function collect(callable $callback): RouteGroup;

    /**
     * Set host for whole route group
     *
     * @param string $host
     * @return RouteGroup
     */
    public function setHost(string $host): RouteGroup;

    /**
     * Set prefix for whole route group
     *
     * @param string $prefix
     * @return RouteGroup
     */
    public function setPrefix(string $prefix): RouteGroup;

    /**
     * Set scheme for whole route group
     *
     * @param string $scheme
     * @return RouteGroup
     */
    public function setScheme(string $scheme): RouteGroup;

}