<?php declare(strict_types = 1);

namespace Venta\Framework\Extension;

use FastRoute\DataGenerator;
use FastRoute\RouteParser as RouteParserContract;
use Venta\Config\ConfigFactory;
use Venta\Console\Command\CommandCollector as CommandCollector;
use Venta\Contracts\Config\ConfigFactory as ConfigFactoryContract;
use Venta\Contracts\Console\CommandCollector as CommandCollectorContract;
use Venta\Contracts\Event\EventDispatcher as EventDispatcherContract;
use Venta\Contracts\Http\RequestFactory as RequestFactoryContract;
use Venta\Contracts\Http\ResponseEmitter as ResponseEmitterContract;
use Venta\Contracts\Routing\DispatcherFactory;
use Venta\Contracts\Routing\MiddlewareCollector as MiddlewareCollectorContract;
use Venta\Contracts\Routing\MiddlewarePipeline as MiddlewarePipelineContract;
use Venta\Contracts\Routing\RouteCollector as RoutingCollectorContract;
use Venta\Contracts\Routing\RouteMatcher as RouteMatcherContract;
use Venta\Contracts\Routing\Strategy;
use Venta\Contracts\Routing\UrlGenerator;
use Venta\Event\EventDispatcher;
use Venta\Framework\Commands\Middlewares;
use Venta\Framework\Commands\RouteMatch;
use Venta\Framework\Commands\Routes;
use Venta\Framework\Commands\Shell;
use Venta\Http\Factory\RequestFactory;
use Venta\Http\ResponseEmitter;
use Venta\Routing\Dispatcher\Factory\GroupCountBasedDispatcherFactory;
use Venta\Routing\Middleware\MiddlewareCollector as MiddlewareCollector;
use Venta\Routing\Middleware\MiddlewarePipeline as MiddlewarePipeline;
use Venta\Routing\RouteCollector as RouteCollector;
use Venta\Routing\RouteMatcher;
use Venta\Routing\RouteParser;
use Venta\Routing\Strategy\Generic;
use Venta\ServiceProvider\AbstractServiceProvider;

/**
 * Class VentaServiceProvider
 *
 * @package Venta\Framework\Extension
 */
class VentaServiceProvider extends AbstractServiceProvider
{
    /**
     * Interface - implementation map to set to the container
     *
     * @var array
     */
    protected $bindings = [
        RouteParserContract::class => RouteParser::class,
        DataGenerator::class => DataGenerator\GroupCountBased::class,
        UrlGenerator::class => RouteCollector::class,
        MiddlewarePipelineContract::class => MiddlewarePipeline::class,
        DispatcherFactory::class => GroupCountBasedDispatcherFactory::class,
        RouteMatcherContract::class => RouteMatcher::class,
        Strategy::class => Generic::class,
    ];

    /**
     * Interface - implementation map of shared bindings
     *
     * @var array
     */
    protected $singletons = [
        RequestFactoryContract::class => RequestFactory::class,
        ResponseEmitterContract::class => ResponseEmitter::class,
        RoutingCollectorContract::class => RouteCollector::class,
        MiddlewareCollectorContract::class => MiddlewareCollector::class,
        CommandCollectorContract::class => CommandCollector::class,
        EventDispatcherContract::class => EventDispatcher::class,
        ConfigFactoryContract::class => ConfigFactory::class,
    ];

    /**
     * @inheritdoc
     */
    public function boot()
    {
        foreach ($this->bindings as $id => $entry) {
            $this->container->set($id, $entry);
        }

        foreach ($this->singletons as $id => $entry) {
            $this->container->share($id, $entry);
        }

        $this->provideCommands(
            Routes::class,
            RouteMatch::class,
            Middlewares::class,
            Shell::class
        );
    }
}