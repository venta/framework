<?php declare(strict_types = 1);

namespace Venta\Framework\Extension;

use FastRoute\DataGenerator;
use FastRoute\RouteParser as RouteParserContract;
use Venta\Config\ConfigFactory;
use Venta\Console\Command\CommandCollector as CommandCollector;
use Venta\Contracts\Config\ConfigFactory as ConfigFactoryContract;
use Venta\Contracts\Console\CommandCollector as CommandCollectorContract;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Event\EventManager as EventManagerContract;
use Venta\Contracts\ExtensionProvider\CommandProvider;
use Venta\Contracts\ExtensionProvider\ServiceProvider;
use Venta\Contracts\Http\RequestFactory as RequestFactoryContract;
use Venta\Contracts\Http\ResponseEmitter as ResponseEmitterContract;
use Venta\Contracts\Routing\DispatcherFactory;
use Venta\Contracts\Routing\MiddlewareCollector as MiddlewareCollectorContract;
use Venta\Contracts\Routing\MiddlewarePipeline as MiddlewarePipelineContract;
use Venta\Contracts\Routing\RouteCollector as RoutingCollectorContract;
use Venta\Contracts\Routing\RouteMatcher as RouteMatcherContract;
use Venta\Contracts\Routing\Strategy;
use Venta\Contracts\Routing\UrlGenerator;
use Venta\Event\EventManager;
use Venta\Framework\Commands\Middlewares;
use Venta\Framework\Commands\RouteMatch;
use Venta\Framework\Commands\Routes;
use Venta\Framework\Commands\Shell;
use Venta\Http\Factory\RequestFactory;
use Venta\Http\ResponseEmitter;
use Venta\Routing\Dispatcher\Factory\GroupCountBasedDispatcherFactory;
use Venta\Routing\Middleware\MiddlewareCollector as MiddlewareCollector;
use Venta\Routing\Middleware\MiddlewarePipeline as MiddlewarePipeline;
use Venta\Routing\RouteParser;
use Venta\Routing\RouteCollector as RouteCollector;
use Venta\Routing\RouteMatcher;
use Venta\Routing\Strategy\Generic;

/**
 * Class VentaExtensionProvider
 *
 * @package Venta\Extension
 */
class VentaExtensionProvider implements ServiceProvider, CommandProvider
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
     * Array of commands to add
     *
     * @var array
     */
    protected $commands = [
        Routes::class,
        RouteMatch::class,
        Middlewares::class,
        Shell::class,
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
        EventManagerContract::class => EventManager::class,
        ConfigFactoryContract::class => ConfigFactory::class,
    ];

    /**
     * @inheritDoc
     */
    public function provideCommands(CommandCollectorContract $collector)
    {
        foreach ($this->commands as $command) {
            $collector->addCommand($command);
        }
    }

    /**
     * @inheritDoc
     */
    public function setServices(Container $container)
    {
        foreach ($this->bindings as $id => $entry) {
            $container->set($id, $entry);
        }

        foreach ($this->singletons as $id => $entry) {
            $container->share($id, $entry);
        }
    }

}