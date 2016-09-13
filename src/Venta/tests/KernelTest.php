<?php

use Abava\Config\Config;
use Abava\Config\Contract\Config as ConfigContract;
use Abava\Config\Contract\Factory;
use Abava\Console\Command\Collector as CommandCollector;
use Abava\Console\Contract\Collector as CommandCollectorContract;
use Abava\Container\Contract\Container;
use Abava\Routing\Collector as RouteCollector;
use Abava\Routing\Contract\Collector as RouteCollectorContract;
use Abava\Routing\Contract\Group;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollectorContract;
use Abava\Routing\Middleware\Collector as MiddlewareCollector;
use PHPUnit\Framework\TestCase;
use Venta\Contract\ExtensionProvider\CommandProvider;
use Venta\Contract\ExtensionProvider\ConfigProvider;
use Venta\Contract\ExtensionProvider\MiddlewareProvider;
use Venta\Contract\ExtensionProvider\RouteProvider;
use Venta\Contract\ExtensionProvider\ServiceProvider;
use Venta\Contract\Kernel;


/**
 * Class KernelTest
 */
class KernelTest extends TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function canBoot()
    {
        $container = Mockery::mock(\Abava\Container\Container::class)->makePartial();
        $container->shouldReceive('share')->with(Container::class, $container, ['container'])->passthru();
        $container->shouldReceive('share')->with(Kernel::class, Mockery::type(Kernel::class), ['kernel']);
        $container->shouldReceive('share')
                  ->with(RouteCollectorContract::class, Mockery::type(Closure::class))
                  ->passthru();
        $container->shouldReceive('share')
                  ->with(MiddlewareCollectorContract::class, Mockery::type(Closure::class))
                  ->passthru();
        $container->shouldReceive('share')
                  ->with(CommandCollectorContract::class, Mockery::type(Closure::class))
                  ->passthru();
        $container->shouldReceive('share')
                  ->with(ConfigContract::class, Mockery::type(Closure::class), ['config'])
                  ->passthru();

        $routeCollector = Mockery::mock(Group::class);
        $routeCollector->shouldReceive('group')->with('/', Mockery::type('callable'))->twice();
        $middlewareCollector = Mockery::mock(MiddlewareCollector::class);
        $commandCollector = Mockery::mock(CommandCollector::class);
        $factory = Mockery::mock(Factory::class);

        $container->shouldReceive('get')->with(RouteCollector::class)->andReturn($routeCollector);
        $container->shouldReceive('get')->with(MiddlewareCollector::class)->andReturn($middlewareCollector);
        $container->shouldReceive('get')->with(CommandCollector::class)->andReturn($commandCollector);
        $container->shouldReceive('get')->with(Config::class)->andReturn(new Config);
        $container->shouldReceive('get')->with(Factory::class)->andReturn($factory);

        // Mocking Extension Provider with listed interfaces implementation
        $extension = Mockery::mock(join(',', [
            RouteProvider::class,
            MiddlewareProvider::class,
            ServiceProvider::class,
            CommandProvider::class,
            ConfigProvider::class,
        ]));
        $extension->shouldReceive('setServices')->with($container)->once();
        $extension->shouldReceive('provideMiddlewares')->with($middlewareCollector)->once();
        $extension->shouldReceive('provideCommands')->with($commandCollector)->once();
        $extension->shouldReceive('provideConfig')->with(Mockery::type(Factory::class))->andReturn(new Config)->once();

        $kernel = new class($container, __DIR__, 'extensions.php') extends \Venta\Kernel
        {

            public function addExtensionProviderInstance($name, $instance)
            {
                $this->extensions[$name] = $instance;
            }

        };

        $kernel->addExtensionProviderInstance('test', $extension);

        $this->assertSame($container, $kernel->boot());
        $container->get(RouteCollectorContract::class);
        $container->get(MiddlewareCollectorContract::class);
        $container->get(CommandCollectorContract::class);
        $container->get('config');
    }

    /**
     * @test
     */
    public function canCreateNewInstance()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('share')->with(Container::class, $container, ['container'])->once();
        $container->shouldReceive('share')->with(Kernel::class, Mockery::on(function ($arg) {
            return $arg instanceof Kernel;
        }), ['kernel'])->once();

        $kernel = new \Venta\Kernel($container, __DIR__, 'extensions.php');

        $this->assertInstanceOf(Kernel::class, $kernel);
    }

    /**
     * @test
     */
    public function canDetectIsCli()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('share');

        $kernel = new \Venta\Kernel($container, __DIR__, 'extensions.php');

        // Assuming nobody will run unit-tests from non-cli environment
        $this->assertTrue($kernel->isCli());
    }

    /**
     * @test
     */
    public function canGetEnvironment()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('share');

        $kernel = new \Venta\Kernel($container, __DIR__, 'extensions.php');

        $this->assertSame('test', $kernel->getEnvironment());
    }

    /**
     * @test
     */
    public function canGetVersion()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('share');

        $kernel = new \Venta\Kernel($container, __DIR__, 'extensions.php');

        $this->assertTrue(is_string($kernel->getVersion()));
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwsExceptionOnInvalidExtensionFile()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('share');

        $kernel = new Venta\Kernel($container, __DIR__, '');
        $kernel->boot();
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwsExceptionOnInvalidExtensionsArray()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('share');

        $kernel = new Venta\Kernel($container, __DIR__, 'broken_extensions_file.php');
        $kernel->boot();
    }

    /**
     * @test
     * @expectedException RuntimeException
     */
    public function throwsExceptionOnNonExistingExtensionFile()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('share');

        $kernel = new Venta\Kernel($container, __DIR__, 'some_random_file');
        $kernel->boot();
    }

}