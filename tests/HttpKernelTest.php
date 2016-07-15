<?php

class HttpKernelTest extends PHPUnit_Framework_TestCase
{

    public function testHandle()
    {
        $request = new \Abava\Http\Request();
        $response = new \Abava\Http\Response();
        $router = $this->getMockBuilder(Router::class)->getMock();
        $router->expects($this->once())->method('dispatch')->with($request)->willReturn($response);
        $app = $this->getMockBuilder(\Venta\Contracts\Application::class)->getMock();
        $app->method('has')->withConsecutive(
            ['request'],
            [\Psr\Http\Message\RequestInterface::class],
            [\Psr\Http\Message\ServerRequestInterface::class],
            [\Abava\Http\Contract\EmitterContract::class],
            [\Psr\Http\Message\ResponseInterface::class],
            ['response']
        )->willReturn(false, false, false, false, false, false);
        $app->method('singleton')->withConsecutive(
            ['request', $request],
            [\Psr\Http\Message\RequestInterface::class, $request],
            [\Psr\Http\Message\ServerRequestInterface::class, $request],
            [\Abava\Http\Contract\EmitterContract::class, \Abava\Http\Emitter::class],
            [\Psr\Http\Message\ResponseInterface::class, $response],
            ['response', $response]
        );
        $app->expects($this->once())->method('bootExtensionProviders');
        $app->expects($this->once())->method('make')->with('router')->willReturn($router);
        $kernel = new \Venta\Kernel\HttpKernel($app);
        $result = $kernel->handle($request);
        $this->assertSame($response, $result);
    }

    public function testEmit()
    {
        $response = new \Abava\Http\Response();
        $emitter = $this->getMockBuilder(\Abava\Http\Contract\EmitterContract::class)->getMock();
        $emitter->method('emit')->with($response);
        $app = $this->getMockBuilder(\Venta\Contracts\Application::class)->getMock();
        $app->method('make')->with(\Abava\Http\Contract\EmitterContract::class)->willReturn($emitter);
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

}
