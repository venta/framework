<?php declare(strict_types = 1);

namespace Venta\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Routing\ImmutableRouteCollection as RouteCollectionContract;

/**
 * Class RequestRouteCollection
 *
 * @package Venta\Routing
 */
final class RequestRouteCollection implements RouteCollectionContract
{

    /**
     * @var ServerRequestInterface
     */
    private $request;

    /**
     * @var RouteCollectionContract
     */
    private $routes;

    /**
     * RequestRouteCollection constructor.
     *
     * @param ServerRequestInterface $request
     * @param RouteCollectionContract $routes
     */
    public function __construct(ServerRequestInterface $request, RouteCollectionContract $routes)
    {
        $this->request = $request;
        $this->routes = $routes;
    }

    /**
     * @inheritDoc
     */
    public function findByName(string $routeName)
    {
        return $this->routes->findByName($routeName);
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        $routes = [];
        foreach ($this->routes->all() as $route) {
            if ((!$route->host() || $route->host() === $this->request->getUri()->getHost())
                && (!$route->scheme() || $route->scheme() === $this->request->getUri()->getScheme())
            ) {
                $routes[] = $route;
            }
        }

        return $routes;
    }

}
