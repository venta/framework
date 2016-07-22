<?php declare(strict_types = 1);

namespace Venta\Kernel;

use Abava\Http\Contract\{
    Emitter as EmitterContract
};
use Abava\Routing\Route;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Application;
use Venta\Contracts\Kernel\HttpKernel as HttpKernelContact;

/**
 * Class HttpKernel
 *
 * @package Venta
 */
class HttpKernel implements HttpKernelContact
{
    /**
     * Application instance holder
     *
     * @var Application
     */
    protected $application;

    /**
     * {@inheritdoc}
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        // binding request instance
        if (!$this->application->has('request')) {
            $this->application->singleton('request', $request);
        }
        if (!$this->application->has(RequestInterface::class) && $request instanceof RequestInterface) {
            $this->application->singleton(RequestInterface::class, $request);
        }
        if (!$this->application->has(ServerRequestInterface::class) && $request instanceof ServerRequestInterface) {
            $this->application->singleton(ServerRequestInterface::class, $request);
        }

        // calling ->bindings() on extension providers
        $this->application->bootExtensionProviders();

        /** @var \Abava\Routing\Contract\Collector $collector */
        $collector = $this->application->make(\Abava\Routing\Contract\Collector::class);
        // Collecting routes from extension providers
        $this->application->routes($collector);
        /** @var \Abava\Routing\Contract\Middleware\Collector $middlewareCollector */
        $middlewareCollector = $this->application->make(\Abava\Routing\Contract\Middleware\Collector::class);
        // Collecting global middlewares from extension providers
        $this->application->middlewares($middlewareCollector);
        /** @var \Abava\Routing\Contract\Matcher $matcher */
        $matcher = $this->application->make(\Abava\Routing\Contract\Matcher::class);
        // Find route matching request
        $route = $matcher->match($request, $collector); // <-- uses FastRoute/Dispatcher to find matching route
        $this->application->singleton('route', $route);
        $this->application->singleton(Route::class, $route);
        // Add route middlewares to the end of global middleware list
        foreach ($route->getMiddlewares() as $name => $m) {
            $middlewareCollector->pushMiddleware($name, $m);
        }
        /** @var \Abava\Routing\Contract\Strategy $strategy */
        $strategy = $this->application->make(\Abava\Routing\Contract\Strategy::class);
        // Wrap controller action call as Closure
        $last = function() use ($strategy, $route) { return $strategy->dispatch($route); };
        /** @var \Abava\Routing\Contract\Middleware\Pipeline $middleware */
        $middleware = $this->application->make(\Abava\Routing\Contract\Middleware\Pipeline::class);
        // Let middleware pipeline handle request, return response and call controller action
        $response = $middleware->handle($request, $last); // <-- here is where all the action begins!

        // bind the latest response instance, it may be used in terminate part
        if (!$this->application->has(ResponseInterface::class)) {
            $this->application->singleton(ResponseInterface::class, $response);
        }
        if (!$this->application->has('response')) {
            $this->application->singleton('response', $response);
        }

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function emit(ResponseInterface $response)
    {
        /** @var EmitterContract $emitter */
        $emitter = $this->application->make(EmitterContract::class);
        $emitter->emit($response);
    }


    /**
     * {@inheritdoc}
     */
    public function terminate()
    {
        $this->application->terminate();
    }

}