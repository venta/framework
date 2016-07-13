<?php declare(strict_types=1);

class ErrorHandlerProviderTest extends PHPUnit_Framework_TestCase
{

    public function testRegisteringHandlers()
    {
        $app = Mockery::mock(\Venta\Framework\Contracts\ApplicationContract::class);
        $provider = new \Venta\Framework\ErrorHandler\ErrorHandlerProvider();
        $provider->bindings($app);
        $handler = Mockery::mock(\Whoops\Handler\HandlerInterface::class);
        $run = Mockery::mock(\Whoops\RunInterface::class);
        $run->shouldReceive('pushHandler')->with($handler);
        $app->shouldReceive('make')->with(\Venta\Framework\ErrorHandler\ErrorHandlerLogger::class)->andReturn($handler);
        $provider->errors($run);
        $middleware = Mockery::mock(\Venta\Routing\Contract\MiddlewareContract::class);
        $app->shouldReceive('make')->with(\Venta\Framework\ErrorHandler\ErrorHandlerMiddleware::class)->andReturn($middleware);
        $collector = Mockery::mock(\Venta\Routing\MiddlewareCollector::class);
        $collector->shouldReceive('addMiddleware')->with('error_handler', $middleware);
        $provider->middlewares($collector);
    }

    public function tearDown()
    {
        Mockery::close();
    }

}
