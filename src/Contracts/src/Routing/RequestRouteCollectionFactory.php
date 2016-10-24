<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface RequestRouteCollectionFactory
 *
 * @package Venta\Routing
 */
interface RequestRouteCollectionFactory
{

    /**
     * Creates request aware route collection.
     *
     * @param RouteCollection $routeCollection
     * @param ServerRequestInterface $request
     * @return RouteCollection
     */
    public function create(RouteCollection $routeCollection, ServerRequestInterface $request): RouteCollection;

}