<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

use Psr\Http\Message\RequestInterface;
use Venta\Routing\Route;

/**
 * Interface RouteMatcher
 *
 * @package Venta\Contracts\Routing
 */
interface RouteMatcher
{

    /**
     * Finds route matching provided request
     *
     * @param RequestInterface $request
     * @param RouteCollector $collector
     * @return Route
     */
    public function match(RequestInterface $request, RouteCollector $collector): Route;

}