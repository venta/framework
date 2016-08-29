<?php

use Abava\Console\Contract\Collector as CommandCollector;
use Abava\Container\Contract\Container;
use Abava\Routing\Contract\Collector as RouteCollector;
use Abava\Routing\Contract\Group;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollector;
use PHPUnit\Framework\TestCase;
use Venta\Contract\ExtensionProvider\Bindings;
use Venta\Contract\ExtensionProvider\Commands;
use Venta\Contract\ExtensionProvider\Middlewares;
use Venta\Contract\ExtensionProvider\Routes;
use Venta\Contract\Kernel;


/**
 * Class KernelTest
 */
class KernelTest extends TestCase
{

    /**
     * @test
     */
    public function canCreateNewInstance()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('singleton')->with(Container::class, $container)->once();
        $container->shouldReceive('singleton')->with(Kernel::class, Mockery::on(function ($arg) {
            return $arg instanceof Kernel;
        }))->once();

        $kernel = new \Venta\Kernel($container, __DIR__, 'extensions.php');

        $this->assertInstanceOf(Kernel::class, $kernel);
    }

    /**
     * @test
     */
    public function canGetVersion()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('singleton');

        $kernel = new \Venta\Kernel($container, __DIR__, 'extensions.php');

        $this->assertTrue(is_string($kernel->getVersion()));
    }

    /**
     * @test
     */
    public function canGetEnvironment()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('singleton');

        $kernel = new \Venta\Kernel($container, __DIR__, 'extensions.php');

        $this->assertSame('test', $kernel->environment());
    }

    /**
     * @test
     */
    public function canDetectIsCli()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('singleton');

        $kernel = new \Venta\Kernel($container, __DIR__, 'extensions.php');

        // Assuming nobody will run unit-tests from non-cli environment
        $this->assertTrue($kernel->isCli());
    }

    /**
     * @test
     */
    public function canBoot()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('singleton');

        $routeCollector = Mockery::mock(Group::class);
        $routeCollector->shouldReceive('group')->with('/', Mockery::type('callable'))->twice();
        $middlewareCollector = Mockery::mock(MiddlewareCollector::class);
        $commandCollector = Mockery::mock(CommandCollector::class);

        $container->shouldReceive('make')->with(RouteCollector::class)->andReturn($routeCollector);
        $container->shouldReceive('make')->with(MiddlewareCollector::class)->andReturn($middlewareCollector);
        $container->shouldReceive('make')->with(CommandCollector::class)->andReturn($commandCollector);


        // Mocking Extension Provider with listed interfaces implementation
        $extension = Mockery::mock(join(', ', [Routes::class, Middlewares::class, Bindings::class, Commands::class]));
        $extension->shouldReceive('bindings')->with($container)->once();
        $extension->shouldReceive('middlewares')->with($middlewareCollector)->once();
        $extension->shouldReceive('commands')->with($commandCollector)->once();

        $kernel = new class($container, __DIR__, 'extensions.php') extends \Venta\Kernel
        {

            public function addExtensionProviderInstance($name, $instance)
            {
                $this->extensions[$name] = $instance;
            }

        };

        $kernel->addExtensionProviderInstance('test', $extension);

        $this->assertSame($container, $kernel->boot());
    }

    public function tearDown()
    {
        Mockery::close();
    }

}