<?php

use Abava\Container\Contract\Container;
use Abava\Http\Contract\Emitter;
use Abava\Routing\Contract\Collector;
use Abava\Routing\Contract\Matcher;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollector;
use Abava\Routing\Contract\Middleware\Pipeline;
use Abava\Routing\Contract\Strategy;
use Abava\Routing\Route;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Venta\Contract\Kernel;

/**
 * Class HttpApplicationTest
 */
class HttpApplicationTest extends TestCase
{
    /**
     * @test
     */
    public function canBootKernel()
    {
        $kernel = Mockery::mock(Kernel::class);
        $kernel->shouldReceive('boot')->withNoArgs()->andReturn(Mockery::mock(Container::class))->once();

        $app = new \Venta\Application\HttpApplication($kernel);

        $this->assertInstanceOf(\Venta\Contract\Application\HttpApplication::class, $app);
    }

    /**
     * @test
     */
    public function canRunHttpApplication()
    {
        // Define mock stubs used in ->run() method
        $container = Mockery::mock(Container::class);
        $request = Mockery::mock(ServerRequestInterface::class);
        $matcher = Mockery::mock(Matcher::class);
        $routeCollector = Mockery::mock(Collector::class);
        $route = Mockery::mock(Route::class);
        $middlewareCollector = Mockery::mock(MiddlewareCollector::class);
        $strategy = Mockery::mock(Strategy::class);
        $pipeline = Mockery::mock(Pipeline::class);
        $emitter = Mockery::mock(Emitter::class);
        $response = Mockery::mock(ResponseInterface::class);
        $kernel = Mockery::mock(Kernel::class);

        // Mock container calls
        $container->shouldReceive('get')->with(ServerRequestInterface::class)->andReturn($request);
        $container->shouldReceive('get')->with(Matcher::class)->andReturn($matcher);
        $container->shouldReceive('get')->with(Collector::class)->andReturn($routeCollector);
        $container->shouldReceive('singleton')->with('route', $route);
        $container->shouldReceive('singleton')->with(Route::class, $route);
        $container->shouldReceive('get')->with(MiddlewareCollector::class)->andReturn($middlewareCollector);
        $container->shouldReceive('get')->with(Strategy::class)->andReturn($strategy);
        $container->shouldReceive('get')->with(Pipeline::class)->andReturn($pipeline);
        $container->shouldReceive('get')->with(Emitter::class)->andReturn($emitter);

        // Mock stub calls
        $matcher->shouldReceive('match')->with($request, $routeCollector)->andReturn($route)->once();
        $route->shouldReceive('getMiddlewares')->withNoArgs()->andReturn(['name' => 'middleware'])->once();
        $middlewareCollector->shouldReceive('pushMiddleware')->with('name', 'middleware')->once();
        $strategy->shouldReceive('dispatch')->with($route)->andReturn($response)->once();
        $pipeline->shouldReceive('handle')
            ->with($request, Mockery::type(Closure::class)
            )->andReturnUsing(function($request, $last){
                return $last($request);
            })->once();
        $emitter->shouldReceive('emit')->with($response)->once();
        $kernel->shouldReceive('boot')->withNoArgs()->andReturn($container)->once();

        // Create and run application
        $app = new \Venta\Application\HttpApplication($kernel);
        $app->run();
    }

    public function tearDown()
    {
        Mockery::close();
    }

}
