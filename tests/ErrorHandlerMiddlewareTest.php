<?php declare(strict_types=1);

namespace Venta\Routing\Contract { interface MiddlewareContract{} }

namespace {

    class ErrorHandlerMiddlewareTest extends PHPUnit_Framework_TestCase
    {

        public function testHandle()
        {
            $e   = new Exception('Message');
            $run = Mockery::mock(\Whoops\RunInterface::class);
            $run->shouldReceive('allowQuit')->with(false);
            $run->shouldReceive('sendHttpCode')->with(false);
            $run->shouldReceive('writeToOutput')->with(false);
            $run->shouldReceive('handleException')->with($e)->andReturn($e->getMessage());
            $responseFactory = Mockery::mock(\Venta\Http\Factory\ResponseFactory::class);
            $responseFactory->shouldReceive('make')->andReturn(new \Venta\Http\Response());
            $middleware = new \Venta\Framework\ErrorHandler\ErrorHandlerMiddleware($run, $responseFactory);
            $request    = Mockery::mock(\Venta\Http\Contract\RequestContract::class);
            $result     = $middleware->handle($request, function () use ($e) { throw $e; });
            $this->assertEquals($e->getMessage(), $result->getBody()->__toString());
            $this->assertEquals(500, $result->getStatusCode());
        }

        public function tearDown()
        {
            Mockery::close();
        }

    }

}