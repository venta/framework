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
        $app->shouldReceive('singleton')->with(\Symfony\Component\Console\Input\InputInterface::class, $input);
        $app->shouldReceive('singleton')->with('output', $output);
        $app->shouldReceive('singleton')->with(\Symfony\Component\Console\Output\OutputInterface::class, $output);

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

    public function testHandingException()
    {
        // Making partial mock for ConsoleKernel class, only doRun method will be mocked
        /** @var \Venta\Kernel\ConsoleKernel|\Mockery\MockInterface $kernel */
        $kernel = Mockery::mock('\Venta\Kernel\ConsoleKernel[doRun]');

        // Creating test input/output
        $input = new \Symfony\Component\Console\Input\ArrayInput([]);
        $output = new \Symfony\Component\Console\Output\NullOutput();

        // Exception to throw
        $e = new Exception();

        // Mocking application
        $app = Mockery::mock(\Venta\Contracts\Application::class);
        $app->shouldReceive('singleton')->with('console', $kernel);
        $app->shouldReceive('singleton')->with('input', $input);
        $app->shouldReceive('singleton')->with(\Symfony\Component\Console\Input\InputInterface::class, $input);
        $app->shouldReceive('singleton')->with('output', $output);
        $app->shouldReceive('singleton')->with(\Symfony\Component\Console\Output\OutputInterface::class, $output);


        $app->shouldReceive('bootExtensionProviders');
        $app->shouldReceive('commands')->with($kernel);

        $app->shouldReceive('has')->with('error_handler')->andReturn(true);
        $errorHandler = Mockery::mock(\Whoops\RunInterface::class);
        $errorHandler->shouldReceive('handleException')->with($e);
        $app->shouldReceive('get')->with('error_handler')->andReturn($errorHandler);

        $app->shouldReceive('bind')->with('status', 1);

        $kernel->setApplication($app);
        $kernel->setAutoExit(false);
        $kernel->shouldReceive('doRun')->with($input, $output)->andThrow($e);
        $result = $kernel->handle($input, $output);

        $this->assertSame(1, $result);
    }

}
