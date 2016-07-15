<?php declare(strict_types=1);

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
            $responseFactory = Mockery::mock(\Abava\Http\Factory\ResponseFactory::class);
            $responseFactory->shouldReceive('make')->andReturn(new \Abava\Http\Response());
            $middleware = new \Venta\ErrorHandler\ErrorHandlerMiddleware($run, $responseFactory);
            $request    = Mockery::mock(\Abava\Http\Contract\Request::class);
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