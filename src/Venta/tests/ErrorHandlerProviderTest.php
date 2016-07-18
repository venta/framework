<?php declare(strict_types=1);

class ErrorHandlerProviderTest extends PHPUnit_Framework_TestCase
{

    public function testRegisteringHandlers()
    {
        $app = Mockery::mock(\Venta\Contracts\Application::class);
        $provider = new \Venta\ErrorHandler\ErrorHandlerProvider();
        $provider->bindings($app);
        $handler = Mockery::mock(\Whoops\Handler\HandlerInterface::class);
        $run = Mockery::mock(\Whoops\RunInterface::class);
        $run->shouldReceive('pushHandler')->with($handler);
        $app->shouldReceive('make')->with(\Venta\ErrorHandler\ErrorHandlerLogger::class)->andReturn($handler);
        $provider->errors($run);
        $middleware = Mockery::mock(\Abava\Routing\Contract\Middleware::class);
        $app->shouldReceive('make')->with(\Venta\ErrorHandler\ErrorHandlerMiddleware::class)->andReturn($middleware);
        $collector = Mockery::mock(\Abava\Routing\MiddlewareCollector::class);
        $collector->shouldReceive('addMiddleware')->with('error_handler', $middleware);
        $provider->middlewares($collector);
    }

    public function tearDown()
    {
        Mockery::close();
    }

}
