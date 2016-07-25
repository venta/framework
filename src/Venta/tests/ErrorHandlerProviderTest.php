<?php declare(strict_types=1);

class ErrorHandlerProviderTest extends PHPUnit_Framework_TestCase
{

    public function testPushingMiddleware()
    {
        $app = Mockery::mock(\Venta\Contracts\Application::class);
        $provider = new \Venta\ErrorHandler\ErrorHandlerProvider();
        $provider->bindings($app);
        $collector = Mockery::mock(\Abava\Routing\Contract\Middleware\Collector::class);
        $collector->shouldReceive('pushMiddleware')->with('error_handler', \Venta\ErrorHandler\ErrorHandlerMiddleware::class);
        $provider->middlewares($collector);
    }

    public function testPushingErrorHandler()
    {
        $errorHandlerLogger = Mockery::mock(\Whoops\Handler\HandlerInterface::class);
        $app = Mockery::mock(\Venta\Contracts\Application::class);
        $app->shouldReceive('make')->with(\Venta\ErrorHandler\ErrorHandlerLogger::class)->andReturn($errorHandlerLogger);
        $provider = new \Venta\ErrorHandler\ErrorHandlerProvider();
        $provider->bindings($app);
        $run = Mockery::mock(\Whoops\RunInterface::class);
        $run->shouldReceive('pushHandler')->with($errorHandlerLogger);
        $provider->errors($run);
    }

    public function tearDown()
    {
        Mockery::close();
    }

}
