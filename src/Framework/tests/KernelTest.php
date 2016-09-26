<?php

use PHPUnit\Framework\TestCase;
use Venta\Config\Config;
use Venta\Console\Command\CommandCollector as CommandCollector;
use Venta\Contracts\Config\Config as ConfigContract;
use Venta\Contracts\Config\ConfigFactory;
use Venta\Contracts\Console\CommandCollector as CommandCollectorContract;
use Venta\Contracts\Container\Container;
use Venta\Contracts\ExtensionProvider\CommandProvider;
use Venta\Contracts\ExtensionProvider\ConfigProvider;
use Venta\Contracts\ExtensionProvider\MiddlewareProvider;
use Venta\Contracts\ExtensionProvider\RouteProvider;
use Venta\Contracts\ExtensionProvider\ServiceProvider;
use Venta\Contracts\Kernel\Kernel;
use Venta\Contracts\Routing\MiddlewareCollector as MiddlewareCollectorContract;
use Venta\Contracts\Routing\RouteCollector as RouteCollectorContract;
use Venta\Contracts\Routing\RouteGroup;
use Venta\Routing\Middleware\MiddlewareCollector as MiddlewareCollector;
use Venta\Routing\RouteCollector as RouteCollector;


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
        $this->markTestSkipped();

        $container = Mockery::mock(\Venta\Container\Container::class)->makePartial();
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

        $routeCollector = Mockery::mock(RouteGroup::class);
        $routeCollector->shouldReceive('group')->with('/', Mockery::type('callable'))->twice();
        $middlewareCollector = Mockery::mock(MiddlewareCollector::class);
        $commandCollector = Mockery::mock(CommandCollector::class);
        $factory = Mockery::mock(ConfigFactory::class);

        $container->shouldReceive('get')->with(RouteCollector::class)->andReturn($routeCollector);
        $container->shouldReceive('get')->with(MiddlewareCollector::class)->andReturn($middlewareCollector);
        $container->shouldReceive('get')->with(CommandCollector::class)->andReturn($commandCollector);
        $container->shouldReceive('get')->with(Config::class)->andReturn(new Config);
        $container->shouldReceive('get')->with(ConfigFactory::class)->andReturn($factory);

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
        $extension->shouldReceive('provideConfig')->with(Mockery::type(ConfigFactory::class))->andReturn(new Config)->once();

        $kernel = new class($container, __DIR__, 'extensions.php') extends \Venta\Framework\Kernel
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

        $kernel = new \Venta\Framework\Kernel($container, __DIR__, 'extensions.php');

        $this->assertInstanceOf(Kernel::class, $kernel);
    }

    /**
     * @test
     */
    public function canDetectIsCli()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('share');

        $kernel = new \Venta\Framework\Kernel($container, __DIR__, 'extensions.php');

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

        $kernel = new \Venta\Framework\Kernel($container, __DIR__, 'extensions.php');

        $this->assertSame('test', $kernel->getEnvironment());
    }

    /**
     * @test
     */
    public function canGetVersion()
    {
        $container = Mockery::mock(Container::class);
        $container->shouldReceive('share');

        $kernel = new \Venta\Framework\Kernel($container, __DIR__, 'extensions.php');

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

        $kernel = new \Venta\Framework\Kernel($container, __DIR__, '');
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

        $kernel = new \Venta\Framework\Kernel($container, __DIR__, 'broken_extensions_file.php');
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

        $kernel = new \Venta\Framework\Kernel($container, __DIR__, 'some_random_file');
        $kernel->boot();
    }

}