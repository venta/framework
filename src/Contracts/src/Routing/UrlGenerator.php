<?php declare(strict_types = 1);


namespace Venta\Contracts\Routing;

use Psr\Http\Message\UriInterface;

/**
 * Interface UrlGenerator
 *
 * @package Venta\Contracts\Routing
 */
interface UrlGenerator
{
    /**
     * Returns an URI of the current route.
     *
     * @param array $variables
     * @param array $query
     * @return UriInterface
     */
    public function toCurrent(array $variables = [], array $query = []): UriInterface;

    /**
     * Returns an URI of the given named route.
     *
     * @param string $routeName
     * @param array $variables
     * @param array $query
     * @return UriInterface
     */
    public function toRoute(string $routeName, array $variables = [], array $query = []): UriInterface;

}