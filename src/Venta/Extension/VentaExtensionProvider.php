<?php declare(strict_types = 1);

namespace Venta\Extension;

use Abava\Console\Command\Collector as CommandCollector;
use Abava\Console\Contract\Collector as CommandCollectorContract;
use Abava\Container\Contract\Caller;
use Abava\Container\Contract\Container;
use Abava\Http\Contract\Emitter as EmitterContract;
use Abava\Http\Contract\RequestFactory as RequestFactoryContract;
use Abava\Http\Emitter;
use Abava\Http\Factory\RequestFactory;
use Abava\Routing\Collector as RouteCollector;
use Abava\Routing\Contract\Collector as RoutingCollectorContract;
use Abava\Routing\Contract\Dispatcher\Factory;
use Abava\Routing\Contract\Matcher as MatcherContract;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollectorContract;
use Abava\Routing\Contract\Middleware\Pipeline as MiddlewarePipelineContract;
use Abava\Routing\Contract\Strategy;
use Abava\Routing\Contract\UrlGenerator;
use Abava\Routing\Dispatcher\Factory\GroupCountBasedFactory;
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
use Venta\Contract\ExtensionProvider\Bindings;
use Venta\Contract\ExtensionProvider\Commands;

/**
 * Class VentaExtensionProvider
 *
 * @package Venta\Extension
 */
class VentaExtensionProvider implements Bindings, Commands
{
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
     * @var Container
     */
    protected $container;

    /**
     * @inheritDoc
     */
    public function bindings(Container $container)
    {
        /*
         * Save Container instance for later use in other methods
         */
        $this->container = $container;

        /**
         * Bind request factory
         */
        if (!$container->has(RequestFactoryContract::class)) {
            $container->share(RequestFactoryContract::class, RequestFactory::class);
        }

        /*
         * Binding response emitter
         */
        if (!$container->has(EmitterContract::class)) {
            $container->share(EmitterContract::class, Emitter::class);
        }

        /*
         * Binding route path parser
         */
        if (!$container->has(RouteParser::class)) {
            $container->set(RouteParser::class, Parser::class);
        }

        /*
         * Binding route parameter parser
         */
        if (!$container->has(DataGenerator::class)) {
            $container->set(DataGenerator::class, DataGenerator\GroupCountBased::class);
        }

        /*
         * Binding route collector
         */
        if (!$container->has(RoutingCollectorContract::class)) {
            $container->share(RoutingCollectorContract::class, RouteCollector::class);
        }

        /*
         * Binding url generator
         */
        if (!$container->has(UrlGenerator::class)) {
            $container->set(UrlGenerator::class, RouteCollector::class);
        }

        /*
         * Binding middleware collector
         */
        if (!$container->has(MiddlewareCollectorContract::class)) {
            $container->share(MiddlewareCollectorContract::class, MiddlewareCollector::class);
        }

        /*
         * Binding middleware pipeline
         */
        if (!$container->has(MiddlewarePipelineContract::class)) {
            $container->set(MiddlewarePipelineContract::class, MiddlewarePipeline::class);
        }

        /*
         * Binding dispatcher (via dispatcher factory)
         */
        if (!$container->has(Factory::class)) {
            $container->set(Factory::class, GroupCountBasedFactory::class);
        }

        /*
         * Binging route matcher
         */
        if (!$container->has(MatcherContract::class)) {
            $container->set(MatcherContract::class, Matcher::class);
        }

        /*
         * Binding dispatch strategy
         */
        if (!$container->has(Strategy::class)) {
            $container->set(Strategy::class, Generic::class);
        }

        /**
         * Binding console command collector
         */
        if (!$container->has(CommandCollectorContract::class)) {
            $container->share(CommandCollectorContract::class, CommandCollector::class);
        }
    }

    /**
     * @inheritDoc
     */
    public function commands(CommandCollectorContract $collector)
    {
        foreach ($this->commands as $command) {
            $collector->addCommand($command);
        }
    }

}