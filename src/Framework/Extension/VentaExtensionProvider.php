<?php declare(strict_types = 1);

namespace Venta\Framework\Extension;

use FastRoute\DataGenerator;
use FastRoute\RouteParser;
use Venta\Config\Contract\Factory as ConfigFactoryContract;
use Venta\Config\Factory as ConfigFactory;
use Venta\Console\Command\Collector as CommandCollector;
use Venta\Console\Contract\Collector as CommandCollectorContract;
use Venta\Container\Contract\Container;
use Venta\Contracts\ExtensionProvider\CommandProvider;
use Venta\Contracts\ExtensionProvider\ServiceProvider;
use Venta\Event\Contract\EventManager as EventManagerContract;
use Venta\Event\EventManager;
use Venta\Framework\Commands\Middlewares;
use Venta\Framework\Commands\RouteMatch;
use Venta\Framework\Commands\Routes;
use Venta\Framework\Commands\Shell;
use Venta\Http\Contract\Emitter as EmitterContract;
use Venta\Http\Contract\RequestFactory as RequestFactoryContract;
use Venta\Http\Emitter;
use Venta\Http\Factory\RequestFactory;
use Venta\Routing\Collector as RouteCollector;
use Venta\Routing\Contract\Collector as RoutingCollectorContract;
use Venta\Routing\Contract\Dispatcher\DispatcherFactory;
use Venta\Routing\Contract\Matcher as MatcherContract;
use Venta\Routing\Contract\Middleware\Collector as MiddlewareCollectorContract;
use Venta\Routing\Contract\Middleware\Pipeline as MiddlewarePipelineContract;
use Venta\Routing\Contract\Strategy;
use Venta\Routing\Contract\UrlGenerator;
use Venta\Routing\Dispatcher\Factory\GroupCountBasedDispatcherFactory;
use Venta\Routing\Matcher;
use Venta\Routing\Middleware\Collector as MiddlewareCollector;
use Venta\Routing\Middleware\Pipeline as MiddlewarePipeline;
use Venta\Routing\Parser;
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
        RouteParser::class => Parser::class,
        DataGenerator::class => DataGenerator\GroupCountBased::class,
        UrlGenerator::class => RouteCollector::class,
        MiddlewarePipelineContract::class => MiddlewarePipeline::class,
        DispatcherFactory::class => GroupCountBasedDispatcherFactory::class,
        MatcherContract::class => Matcher::class,
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
        EmitterContract::class => Emitter::class,
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