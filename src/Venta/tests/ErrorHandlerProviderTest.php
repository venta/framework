<?php declare(strict_types = 1);

class ErrorHandlerProviderTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    public function testPushingErrorHandler()
    {
        $this->markTestSkipped();

        $errorHandlerLogger = Mockery::mock(\Whoops\Handler\HandlerInterface::class);
        $app = Mockery::mock(\Venta\Contract\Application::class);
        $app->shouldReceive('make')
            ->with(\Venta\ErrorHandler\ErrorHandlerLogger::class)
            ->andReturn($errorHandlerLogger);
        $provider = new \Venta\ErrorHandler\ErrorHandlerProvider();
        $provider->setServices($app);
        $run = Mockery::mock(\Whoops\RunInterface::class);
        $run->shouldReceive('pushHandler')->with($errorHandlerLogger);
        $provider->errors($run);
    }

    public function testPushingMiddleware()
    {
        $this->markTestSkipped();

        $app = Mockery::mock(\Venta\Contract\Application::class);
        $provider = new \Venta\ErrorHandler\ErrorHandlerProvider();
        $provider->setServices($app);
        $collector = Mockery::mock(\Abava\Routing\Contract\Middleware\Collector::class);
        $collector->shouldReceive('pushMiddleware')
                  ->with('error_handler', \Venta\ErrorHandler\ErrorHandlerMiddleware::class);
        $provider->provideMiddlewares($collector);
    }

}
