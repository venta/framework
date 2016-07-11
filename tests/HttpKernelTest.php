<?php

class HttpKernelTest extends PHPUnit_Framework_TestCase
{

    public function testHandle()
    {
        $request = new \Venta\Http\Request();
        $response = new \Venta\Http\Response();
        $router = $this->getMockBuilder(Router::class)->getMock();
        $router->expects($this->once())->method('dispatch')->with($request)->willReturn($response);
        $app = $this->getMockBuilder(\Venta\Framework\Contracts\ApplicationContract::class)->getMock();
        $app->method('has')->withConsecutive(
            ['request'],
            [\Psr\Http\Message\RequestInterface::class],
            [\Psr\Http\Message\ServerRequestInterface::class],
            [\Venta\Http\Contract\EmitterContract::class],
            [\Psr\Http\Message\ResponseInterface::class],
            ['response']
        )->willReturn(false, false, false, false, false, false);
        $app->method('singleton')->withConsecutive(
            ['request', $request],
            [\Psr\Http\Message\RequestInterface::class, $request],
            [\Psr\Http\Message\ServerRequestInterface::class, $request],
            [\Venta\Http\Contract\EmitterContract::class, \Venta\Http\Emitter::class],
            [\Psr\Http\Message\ResponseInterface::class, $response],
            ['response', $response]
        );
        $app->expects($this->once())->method('bootExtensionProviders');
        $app->expects($this->once())->method('make')->with('router')->willReturn($router);
        $kernel = new \Venta\Framework\Kernel\HttpKernel($app);
        $result = $kernel->handle($request);
        $this->assertSame($response, $result);
    }

    public function testEmit()
    {
        $response = new \Venta\Http\Response();
        $emitter = $this->getMockBuilder(\Venta\Http\Contract\EmitterContract::class)->getMock();
        $emitter->method('emit')->with($response);
        $app = $this->getMockBuilder(\Venta\Framework\Contracts\ApplicationContract::class)->getMock();
        $app->method('make')->with(\Venta\Http\Contract\EmitterContract::class)->willReturn($emitter);
        $kernel = new \Venta\Framework\Kernel\HttpKernel($app);
        $kernel->emit($response);
    }

    public function testTerminate()
    {
        $app = $this->getMockBuilder(\Venta\Framework\Contracts\ApplicationContract::class)->getMock();
        $app->method('terminate');
        $kernel = new \Venta\Framework\Kernel\HttpKernel($app);
        $kernel->terminate();
    }

}
