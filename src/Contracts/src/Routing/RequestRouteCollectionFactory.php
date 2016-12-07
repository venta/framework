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
     * @param ImmutableRouteCollection $routeCollection
     * @param ServerRequestInterface $request
     * @return ImmutableRouteCollection
     */
    public function create(
        ImmutableRouteCollection $routeCollection,
        ServerRequestInterface $request
    ): ImmutableRouteCollection;

}