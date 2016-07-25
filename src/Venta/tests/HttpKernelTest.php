<?php

class HttpKernelTest extends PHPUnit_Framework_TestCase
{

    public function testHandle()
    {
        $app = Mockery::mock(\Venta\Contracts\Application::class);
        $request = Mockery::mock(\Psr\Http\Message\ServerRequestInterface::class);
        $response = Mockery::mock(\Psr\Http\Message\ResponseInterface::class);

        // Mock bindings to app container
        $app->shouldReceive('has')->with('request');
        $app->shouldReceive('singleton')->with('request', $request);
        $app->shouldReceive('has')->with(\Psr\Http\Message\RequestInterface::class);
        $app->shouldReceive('singleton')->with(\Psr\Http\Message\RequestInterface::class, $request);
        $app->shouldReceive('has')->with(\Psr\Http\Message\ServerRequestInterface::class);
        $app->shouldReceive('singleton')->with(\Psr\Http\Message\ServerRequestInterface::class, $request);
        $app->shouldReceive('has')->with(\Psr\Http\Message\ResponseInterface::class);
        $app->shouldReceive('singleton')->with(\Psr\Http\Message\ResponseInterface::class, $response);
        $app->shouldReceive('has')->with('response');
        $app->shouldReceive('singleton')->with('response', $response);

        // Mock calling bootExtensionProviders method
        $app->shouldReceive('bootExtensionProviders')->withNoArgs();

        // Mock route collector usage
        $routeCollector = Mockery::mock(\Abava\Routing\Contract\Collector::class);
        $app->shouldReceive('make')->with(\Abava\Routing\Contract\Collector::class)->andReturn($routeCollector);
        $app->shouldReceive('routes')->with($routeCollector);

        // Mock middleware collector usage
        $middlewareCollector = Mockery::mock(\Abava\Routing\Contract\Middleware\Collector::class);
        $app->shouldReceive('make')->with(\Abava\Routing\Contract\Middleware\Collector::class)->andReturn($middlewareCollector);
        $app->shouldReceive('middlewares')->with($middlewareCollector);

        // Mock route matcher usage
        $matcher = Mockery::mock(\Abava\Routing\Contract\Matcher::class);
        $app->shouldReceive('make')->with(\Abava\Routing\Contract\Matcher::class)->andReturn($matcher);
        $route = Mockery::mock(\Abava\Routing\Route::class);
        $matcher->shouldReceive('match')->with($request, $routeCollector)->andReturn($route);

        // Mock current (matched) route binding
        $app->shouldReceive('singleton')->with('route', $route);
        $app->shouldReceive('singleton')->with(\Abava\Routing\Route::class, $route);

        // Mock pushing route's middleware to collector
        $route->shouldReceive('getMiddlewares')->withNoArgs()->andReturn(['name' => 'middleware']);
        $middlewareCollector->shouldReceive('pushMiddleware')->once()->with('name', 'middleware');

        // Mock strategy and middleware pipeline usage
        $strategy = Mockery::mock(\Abava\Routing\Contract\Strategy::class);
        $app->shouldReceive('make')->with(\Abava\Routing\Contract\Strategy::class)->andReturn($strategy);
        $strategy->shouldReceive('dispatch')->with($route)->andReturn($response);
        $middleware = Mockery::mock(\Abava\Routing\Contract\Middleware\Pipeline::class);
        $app->shouldReceive('make')->with(\Abava\Routing\Contract\Middleware\Pipeline::class)->andReturn($middleware);
        $middleware->shouldReceive('handle')
            ->with($request, Mockery::type('callable'))
            ->andReturnUsing(function($request, $last){ return $last($request); });

        $kernel = new \Venta\Kernel\HttpKernel($app);
        $result = $kernel->handle($request);
        $this->assertSame($response, $result);
    }

    public function testEmit()
    {
        $response = Mockery::mock(\Psr\Http\Message\ResponseInterface::class);
        $emitter = Mockery::mock(\Abava\Http\Contract\Emitter::class);
        $emitter->shouldReceive('emit')->with($response);
        $app = Mockery::mock(\Venta\Contracts\Application::class);
        $app->shouldReceive('make')->with(\Abava\Http\Contract\Emitter::class)->andReturn($emitter);
        $kernel = new \Venta\Kernel\HttpKernel($app);
        $kernel->emit($response);
    }

    public function testTerminate()
    {
        $app = $this->getMockBuilder(\Venta\Contracts\Application::class)->getMock();
        $app->method('terminate');
        $kernel = new \Venta\Kernel\HttpKernel($app);
        $kernel->terminate();
    }

    public function tearDown()
    {
        Mockery::close();
    }

}
