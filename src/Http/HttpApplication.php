<?php declare(strict_types = 1);

namespace Venta\Http;

use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Http\ResponseEmitter as EmitterContract;
use Venta\Contracts\Kernel;
use Venta\Contracts\Routing\MiddlewareCollector as MiddlewareCollector;
use Venta\Contracts\Routing\MiddlewarePipeline;
use Venta\Contracts\Routing\RouteCollector;
use Venta\Contracts\Routing\RouteMatcher;
use Venta\Contracts\Routing\Strategy;
use Venta\Routing\Route;

/**
 * Class HttpApplication
 *
 * @package Venta\Application
 */
class HttpApplication implements \Venta\Contracts\Http\HttpApplication
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * HttpApplication constructor.
     *
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->container = $kernel->boot();
    }

    /**
     * @inheritDoc
     */
    public function run()
    {
        /*
        |--------------------------------------------------------------------------
        | Making request
        |--------------------------------------------------------------------------
        |
        | Getting ServerRequest instance from container
        */
        /** @var ServerRequestInterface $request */
        $request = $this->container->get(ServerRequestInterface::class);

        /*
        |--------------------------------------------------------------------------
        | Matching route
        |--------------------------------------------------------------------------
        |
        | Find route matching provided request instance
        */
        /** @var RouteMatcher $matcher */
        $matcher = $this->container->get(RouteMatcher::class);
        /** @var RouteCollector $routeCollector */
        $routeCollector = $this->container->get(RouteCollector::class);
        $route = $matcher->match($request, $routeCollector);

        /*
        |--------------------------------------------------------------------------
        | Binding route
        |--------------------------------------------------------------------------
        |
        | Binding current route instance to container for later use
        */
        $this->container->share(Route::class, $route, ['route']);

        /*
        |--------------------------------------------------------------------------
        | Pushing route middlewares
        |--------------------------------------------------------------------------
        |
        | Route may have its own middlewares. Push them to the end of
        | the middleware stack
        */
        /** @var \Venta\Contracts\Routing\MiddlewareCollector $middlewareCollector */
        $middlewareCollector = $this->container->get(MiddlewareCollector::class);
        foreach ($route->getMiddlewares() as $name => $middleware) {
            $middlewareCollector->pushMiddleware($name, $middleware);
        }

        /*
        |--------------------------------------------------------------------------
        | Handle route action
        |--------------------------------------------------------------------------
        |
        | Create last middleware from route callable (action) using strategy
        */
        /** @var Strategy $strategy */
        $strategy = $this->container->get(Strategy::class);
        $last = function () use ($strategy, $route) {
            return $strategy->dispatch($route);
        };

        /*
        |--------------------------------------------------------------------------
        | Make middleware pipeline
        |--------------------------------------------------------------------------
        |
        | Pass request to middleware pipeline to get response in return
        */
        /** @var MiddlewarePipeline $middlewarePipeline */
        $middlewarePipeline = $this->container->get(MiddlewarePipeline::class);
        $response = $middlewarePipeline->handle($request, $last);

        /*
        |--------------------------------------------------------------------------
        | Emit response
        |--------------------------------------------------------------------------
        |
        | Emit response to the client - send headers and body
        */
        /** @var EmitterContract $emitter */
        $emitter = $this->container->get(EmitterContract::class);
        $emitter->emit($response);
    }

}