<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface RoutePathProcessor
 *
 * @package Venta\Contracts\Routing
 */
interface RoutePathProcessor extends RouteProcessor
{

    /**
     * Sets regex pattern to apply to specified route path placeholder.
     *
     * @param string $placeholder
     * @param string $regex
     * @return RoutePathProcessor
     */
    public function addPattern(string $placeholder, string $regex): RoutePathProcessor;

}