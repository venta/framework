<?php

class ConsoleKernelTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    public function testHandle()
    {
        // Making partial mock for ConsoleKernel class, only doRun method will be mocked
        /** @var \Venta\Kernel\ConsoleKernel|\Mockery\MockInterface $kernel */
        $kernel = Mockery::mock('\Venta\Kernel\ConsoleKernel[doRun]');

        // Creating test input/output
        $input = new \Symfony\Component\Console\Input\ArrayInput([]);
        $output = new \Symfony\Component\Console\Output\NullOutput();

        // Mocking application
        $app = Mockery::mock(\Venta\Contracts\Application::class);
        $app->shouldReceive('singleton')->with('console', $kernel);
        $app->shouldReceive('singleton')->with('input', $input);
        $app->shouldReceive('singleton')->with('output', $output);

        $app->shouldReceive('bootExtensionProviders');
        $app->shouldReceive('commands')->with($kernel);

        $app->shouldReceive('bind')->with('status', 0);

        $kernel->setApplication($app);
        $kernel->setAutoExit(false);
        $kernel->shouldReceive('doRun')->with($input, $output)->andReturn(0);
        $result = $kernel->handle($input, $output);

        $this->assertSame(0, $result);
    }

    public function testTerminate()
    {
        $app = $this->getMockBuilder(\Venta\Contracts\Application::class)->getMock();
        $app->method('terminate');
        $kernel = new \Venta\Kernel\ConsoleKernel('test', '1.0');
        $kernel->setApplication($app);
        $kernel->terminate();
    }

}
