<?php declare(strict_types = 1);

namespace Venta\Extension;

use Abava\Console\Command\Collector as CommandCollector;
use Abava\Console\Contract\Collector as CommandCollectorContract;
use Abava\Container\Contract\Container;
use Abava\Http\Contract\Emitter as EmitterContract;
use Abava\Http\Contract\RequestFactory as RequestFactoryContract;
use Abava\Http\Emitter;
use Abava\Http\Factory\RequestFactory;
use Abava\Routing\Collector as RouteCollector;
use Abava\Routing\Contract\Collector as RoutingCollectorContract;
use Abava\Routing\Contract\Dispatcher\DispatcherFactory;
use Abava\Routing\Contract\Matcher as MatcherContract;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollectorContract;
use Abava\Routing\Contract\Middleware\Pipeline as MiddlewarePipelineContract;
use Abava\Routing\Contract\Strategy;
use Abava\Routing\Contract\UrlGenerator;
use Abava\Routing\Dispatcher\Factory\GroupCountBasedDispatcherFactory;
use Abava\Routing\Matcher;
use Abava\Routing\Middleware\Collector as MiddlewareCollector;
use Abava\Routing\Middleware\Pipeline as MiddlewarePipeline;
use Abava\Routing\Parser;
use Abava\Routing\Strategy\Generic;
use FastRoute\DataGenerator;
use FastRoute\RouteParser;
use Venta\Commands\Middlewares;
use Venta\Commands\RouteMatch;
use Venta\Commands\Routes;
use Venta\Commands\Shell;
use Venta\Contract\ExtensionProvider\CommandProvider;
use Venta\Contract\ExtensionProvider\ServiceProvider;

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