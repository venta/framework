<?php declare(strict_types = 1);

namespace Abava\Routing;

use Abava\Container\Contract\Caller;
use Abava\Http\Contract\{
    Response
};
use Abava\Routing\Contract\{
    Middleware, Router as RouterContract
};
use Abava\Routing\Exceptions\{
    NotAllowedException, NotFoundException
};
use FastRoute\Dispatcher\GroupCountBased;
use FastRoute\RouteParser\Std;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Router
 *
 * @package Abava\Routing
 */
class Router implements RouterContract
{
    /**
     * Container instance holder
     *
     * @var Caller
     */
    protected $caller;

    /**
     * Dispatcher instance holder
     *
     * @var GroupCountBased
     */
    protected $dispatcher;

    /**
     * Collection of defined middleware
     *
     * @var MiddlewareCollector
     */
    protected $middleware;

    /**
     * Router constructor.
     *
     * @param Caller $caller
     * @param MiddlewareCollector $middlewareCollector
     * @param callable $collectionCallback
     */
    public function __construct(Caller $caller, MiddlewareCollector $middlewareCollector, callable $collectionCallback)
    {
        $this->middleware = $middlewareCollector;
        $this->caller = $caller;
        $this->collectRoutes($collectionCallback);
    }

    /**
     * Collect middlewares with passed in collector callable
     *
     * @param callable $collectionCallback
     * @return $this
     */
    public function collectMiddlewares(callable $collectionCallback)
    {
        $collectionCallback($this->middleware);
        return $this;
    }

    /**
     * Dispatch router
     * Find matching route, pass through middlewares, fire controller action, return response
     *
     * @param RequestInterface $request Request
     * @return ResponseInterface
     */
    public function dispatch(RequestInterface $request): ResponseInterface
    {
        $match = $this->dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        switch ($match[0]) {
            case GroupCountBased::FOUND:
                $pipe = $this->buildMiddlewarePipeline($match[1], $match[2]);
                return $pipe($request);
                break;
            case GroupCountBased::METHOD_NOT_ALLOWED:
                throw new NotAllowedException($match[1]);
            default:
                throw new NotFoundException;
        }
    }

    /**
     * Collect routes with passed in collector callable
     *
     * @param  callable $collectionCallback
     * @return $this
     */
    protected function collectRoutes(callable $collectionCallback)
    {
        $collector = new RoutesCollector(new Std, new \FastRoute\DataGenerator\GroupCountBased);
        $collectionCallback($collector);

        $this->dispatcher = new GroupCountBased($collector->getRoutesCollection());

        return $this;
    }

    /**
     * Handles found route
     *
     * @param  \Closure|string $handler
     * @param  array $parameters
     * @return Response
     * @throws \RuntimeException
     */
    protected function handleFoundRoute($handler, array $parameters): Response
    {
        $response = $this->caller->call($handler, $parameters);

        if ($response instanceof Response) {
            // Response should be returned directly
            return $response;
        }

        if (is_object($response) && method_exists($response, '__toString')) {
            // Try to get string out of object as last fallback
            $response = $response->__toString();
        }

        if (is_string($response)) {
            // String supposed to be appended to response body
            return $this->caller->call('\Abava\Http\Factory\ResponseFactory@new')->append($response);
        }

        throw new \RuntimeException('Controller action result must be either ResponseInterface or string');
    }

    /**
     * Build pipeline to be executed before route handler
     *
     * @param  mixed $handler
     * @param  array $parameters
     * @return \Closure
     */
    protected function buildMiddlewarePipeline($handler, array $parameters): \Closure
    {
        $next = $this->getLastStep($handler, $parameters);

        foreach ($this->middleware->getMiddlewares() as $class) {
            $next = function (RequestInterface $request) use ($class, $next) {
                /** @var Middleware $class */
                return $class->handle($request, $next);
            };
        }

        return $next;
    }

    /**
     * Returns middleware pipeline last step
     *
     * @param  mixed $handler
     * @param  array $parameters
     * @return \Closure
     */
    protected function getLastStep($handler, array $parameters): \Closure
    {
        return function () use ($handler, $parameters) {
            return $this->handleFoundRoute($handler, $parameters);
        };
    }
}