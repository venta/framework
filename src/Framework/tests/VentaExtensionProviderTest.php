<?php

use FastRoute\DataGenerator;
use FastRoute\RouteParser;
use PHPUnit\Framework\TestCase;
use Venta\Console\Contract\Collector as CommandCollectorContract;
use Venta\Container\Container;
use Venta\Framework\Commands\Middlewares;
use Venta\Framework\Commands\RouteMatch;
use Venta\Framework\Commands\Routes;
use Venta\Framework\Commands\Shell;
use Venta\Framework\Extension\VentaExtensionProvider;
use Venta\Http\Contract\Emitter;
use Venta\Http\Contract\RequestFactory;
use Venta\Routing\Contract\Collector as RoutingCollectorContract;
use Venta\Routing\Contract\Dispatcher\DispatcherFactory;
use Venta\Routing\Contract\Matcher;
use Venta\Routing\Contract\Middleware\Collector as MiddlewareCollectorContract;
use Venta\Routing\Contract\Middleware\Pipeline;
use Venta\Routing\Contract\Strategy;
use Venta\Routing\Contract\UrlGenerator;

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
        $collector = Mockery::mock(CommandCollectorContract::class);
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
        $this->assertTrue($container->has(Pipeline::class));
        $this->assertTrue($container->has(DispatcherFactory::class));
        $this->assertTrue($container->has(Matcher::class));
        $this->assertTrue($container->has(Strategy::class));
        $this->assertTrue($container->has(RequestFactory::class));
        $this->assertTrue($container->has(Emitter::class));
        $this->assertTrue($container->has(RoutingCollectorContract::class));
        $this->assertTrue($container->has(MiddlewareCollectorContract::class));
        $this->assertTrue($container->has(CommandCollectorContract::class));
    }

}
