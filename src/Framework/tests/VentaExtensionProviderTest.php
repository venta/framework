<?php

use FastRoute\DataGenerator;
use FastRoute\RouteParser;
use PHPUnit\Framework\TestCase;
use Venta\Container\Container;
use Venta\Contracts\Console\CommandCollector;
use Venta\Contracts\Http\RequestFactory;
use Venta\Contracts\Http\ResponseEmitter;
use Venta\Contracts\Routing\DispatcherFactory;
use Venta\Contracts\Routing\MiddlewareCollector;
use Venta\Contracts\Routing\MiddlewarePipeline;
use Venta\Contracts\Routing\RouteCollector;
use Venta\Contracts\Routing\RouteMatcher;
use Venta\Contracts\Routing\Strategy;
use Venta\Contracts\Routing\UrlGenerator;
use Venta\Framework\Commands\Middlewares;
use Venta\Framework\Commands\RouteMatch;
use Venta\Framework\Commands\Routes;
use Venta\Framework\Commands\Shell;
use Venta\Framework\Extension\VentaExtensionProvider;

class VentaExtensionProviderTest extends TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function canAddCommandsToCollector()
    {
        $collector = Mockery::mock(CommandCollector::class);
        $collector->shouldReceive('addCommand')->with(Routes::class)->once();
        $collector->shouldReceive('addCommand')->with(RouteMatch::class)->once();
        $collector->shouldReceive('addCommand')->with(Middlewares::class)->once();
        $collector->shouldReceive('addCommand')->with(Shell::class)->once();

        $provider = new VentaExtensionProvider();
        $provider->provideCommands($collector);
    }

    /**
     * @test
     */
    public function canSetContainerBindings()
    {
        $container = new Container();
        $provider = new VentaExtensionProvider();
        $provider->setServices($container);

        $this->assertTrue($container->has(RouteParser::class));
        $this->assertTrue($container->has(DataGenerator::class));
        $this->assertTrue($container->has(UrlGenerator::class));
        $this->assertTrue($container->has(MiddlewarePipeline::class));
        $this->assertTrue($container->has(DispatcherFactory::class));
        $this->assertTrue($container->has(RouteMatcher::class));
        $this->assertTrue($container->has(Strategy::class));
        $this->assertTrue($container->has(RequestFactory::class));
        $this->assertTrue($container->has(ResponseEmitter::class));
        $this->assertTrue($container->has(RouteCollector::class));
        $this->assertTrue($container->has(MiddlewareCollector::class));
        $this->assertTrue($container->has(CommandCollector::class));
    }

}
