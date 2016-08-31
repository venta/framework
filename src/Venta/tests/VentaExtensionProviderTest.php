<?php

use Abava\Console\Contract\Collector as CommandCollectorContract;
use Abava\Container\Container;
use Abava\Http\Contract\Emitter;
use Abava\Http\Contract\RequestFactory;
use Abava\Routing\Contract\Collector as RoutingCollectorContract;
use Abava\Routing\Contract\Dispatcher\DispatcherFactory;
use Abava\Routing\Contract\Matcher;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollectorContract;
use Abava\Routing\Contract\Middleware\Pipeline;
use Abava\Routing\Contract\Strategy;
use Abava\Routing\Contract\UrlGenerator;
use FastRoute\DataGenerator;
use FastRoute\RouteParser;
use PHPUnit\Framework\TestCase;
use Venta\Commands\Middlewares;
use Venta\Commands\RouteMatch;
use Venta\Commands\Routes;
use Venta\Commands\Shell;
use Venta\Extension\VentaExtensionProvider;

class VentaExtensionProviderTest extends TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function canSetContainerBindings()
    {
        $container = new Container();
        $provider = new VentaExtensionProvider();
        $provider->bindings($container);

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
        $provider->commands($collector);
    }

}
