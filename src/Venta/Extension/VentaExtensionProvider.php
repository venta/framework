<?php declare(strict_types = 1);

namespace Venta\Extension;

use Abava\Console\Command\Collector as CommandCollector;
use Abava\Console\Contract\Collector as CommandCollectorContract;
use Abava\Container\Contract\Caller;
use Abava\Container\Contract\Container;
use Abava\Http\Contract\Emitter as EmitterContract;
use Abava\Http\Emitter;
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
use Venta\Contract\ExtensionProvider\Bindings;

/**
 * Class VentaExtensionProvider
 *
 * @package Venta\Extension
 */
class VentaExtensionProvider implements Bindings
{

    /**
     * @inheritDoc
     */
    public function bindings(Container $container)
    {
        /*
         * Binding response emitter
         */
        if (!$container->has(EmitterContract::class)) {
            $container->singleton(EmitterContract::class, Emitter::class);
        }

        /*
         * Binding route path parser
         */
        if (!$container->has(RouteParser::class)) {
            $container->bind(RouteParser::class, Parser::class);
        }

        /*
         * Binding route parameter parser
         */
        if (!$container->has(DataGenerator::class)) {
            $container->bind(DataGenerator::class, DataGenerator\GroupCountBased::class);
        }

        /*
         * Binding route collector
         */
        if (!$container->has(RoutingCollectorContract::class)) {
            $container->singleton(RoutingCollectorContract::class, RouteCollector::class);
        }

        /*
         * Binding url generator
         */
        if (!$container->has(UrlGenerator::class)) {
            $container->bind(UrlGenerator::class, RouteCollector::class);
        }

        /*
         * Binding middleware collector
         */
        if (!$container->has(MiddlewareCollectorContract::class)) {
            $container->singleton(MiddlewareCollectorContract::class, MiddlewareCollector::class);
        }

        /*
         * Binding middleware pipeline
         */
        if (!$container->has(MiddlewarePipelineContract::class)) {
            $container->bind(MiddlewarePipelineContract::class, MiddlewarePipeline::class);
        }

        /*
         * Binding dispatcher (via dispatcher factory)
         */
        if (!$container->has(Factory::class)) {
            $container->bind(Factory::class, GroupCountBasedFactory::class);
        }

        /*
         * Binging route matcher
         */
        if (!$container->has(MatcherContract::class)) {
            $container->bind(MatcherContract::class, Matcher::class);
        }

        /*
         * Binding dispatch strategy
         */
        if (!$container->has(Strategy::class)) {
            $container->bind(Strategy::class, Generic::class);
        }

        /**
         * Binding console command collector
         */
        if (!$container->has(CommandCollectorContract::class)) {
            $container->singleton(CommandCollectorContract::class, CommandCollector::class);
        }

        /*
         * Binding caller contract to container instance
         */
        if (!$container->has(Caller::class)) {
            $container->singleton(Caller::class, $container);
        }
    }

}