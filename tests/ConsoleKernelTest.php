<?php

class ConsoleKernelTest extends PHPUnit_Framework_TestCase
{

    public function testHandle()
    {
        // todo Unskip this test when appropriate callExtensionProvidersMethod will be available
        $this->markTestSkipped('callExtensionProvidersMethod fallback required');

        $app = $this->getMockBuilder(\Venta\Contracts\ApplicationContract::class)->getMock();
        $app->method('version')->willReturn('1.0');
        $app->method('bind');
        $app->method('bootExtensionProviders');

        $proxy->app = $app;

        $kernel = new \Venta\Kernel\ConsoleKernel($proxy);
        $result = $kernel->handle(
            new \Symfony\Component\Console\Input\ArrayInput([]),
            new \Symfony\Component\Console\Output\NullOutput
        );
        $this->assertSame(0, $result);
    }

    public function testTerminate()
    {
        $app = $this->getMockBuilder(\Venta\Contracts\ApplicationContract::class)->getMock();
        $app->method('terminate');
        $kernel = new \Venta\Kernel\ConsoleKernel($app);
        $kernel->terminate();
    }

}
