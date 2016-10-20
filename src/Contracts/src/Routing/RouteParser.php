<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface RouteParser
 *
 * @package Contracts\src\Routing
 */
interface RouteParser
{

    /**
     * Parses route array to pass to dispatcher.
     *
     * @param Route[] $routes
     * @return array
     */
    public function parse(array $routes): array;

}