<?php

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Http\ResponseEmitter;
use Venta\Contracts\Kernel;
use Venta\Contracts\Routing\MiddlewareCollector as MiddlewareCollector;
use Venta\Contracts\Routing\MiddlewarePipeline;
use Venta\Contracts\Routing\RouteCollector;
use Venta\Contracts\Routing\RouteMatcher;
use Venta\Contracts\Routing\Strategy;
use Venta\Routing\Route;

/**
 * Class HttpApplicationTest
 */
class HttpApplicationTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function canBootKernel()
    {
        $kernel = Mockery::mock(Kernel::class);
        $kernel->shouldReceive('boot')->withNoArgs()->andReturn(Mockery::mock(Container::class))->once();

        $app = new \Venta\Http\HttpApplication($kernel);

        $this->assertInstanceOf(\Venta\Contracts\Application\HttpApplication::class, $app);
    }

    /**
     * @test
     */
    public function canRunHttpApplication()
    {
        // Define mock stubs used in ->run() method
        $container = Mockery::mock(Container::class);
        $request = Mockery::mock(ServerRequestInterface::class);
        $matcher = Mockery::mock(RouteMatcher::class);
        $routeCollector = Mockery::mock(RouteCollector::class);
        $route = Mockery::mock(Route::class);
        $middlewareCollector = Mockery::mock(MiddlewareCollector::class);
        $strategy = Mockery::mock(Strategy::class);
        $pipeline = Mockery::mock(MiddlewarePipeline::class);
        $emitter = Mockery::mock(ResponseEmitter::class);
        $response = Mockery::mock(ResponseInterface::class);
        $kernel = Mockery::mock(Kernel::class);

        // Mock container calls
        $container->shouldReceive('get')->with(ServerRequestInterface::class)->andReturn($request);
        $container->shouldReceive('get')->with(RouteMatcher::class)->andReturn($matcher);
        $container->shouldReceive('get')->with(RouteCollector::class)->andReturn($routeCollector);
        $container->shouldReceive('share')->with(Route::class, $route, ['route']);
        $container->shouldReceive('get')->with(MiddlewareCollector::class)->andReturn($middlewareCollector);
        $container->shouldReceive('get')->with(Strategy::class)->andReturn($strategy);
        $container->shouldReceive('get')->with(MiddlewarePipeline::class)->andReturn($pipeline);
        $container->shouldReceive('get')->with(ResponseEmitter::class)->andReturn($emitter);

        // Mock stub calls
        $matcher->shouldReceive('match')->with($request, $routeCollector)->andReturn($route)->once();
        $route->shouldReceive('getMiddlewares')->withNoArgs()->andReturn(['name' => 'middleware'])->once();
        $middlewareCollector->shouldReceive('pushMiddleware')->with('name', 'middleware')->once();
        $strategy->shouldReceive('dispatch')->with($route)->andReturn($response)->once();
        $pipeline->shouldReceive('handle')
                 ->with($request, Mockery::type(Closure::class)
                 )->andReturnUsing(function ($request, $last) {
                return $last($request);
            })->once();
        $emitter->shouldReceive('emit')->with($response)->once();
        $kernel->shouldReceive('boot')->withNoArgs()->andReturn($container)->once();

        // Create and run application
        $app = new \Venta\Http\HttpApplication($kernel);
        $app->run();
    }

}
