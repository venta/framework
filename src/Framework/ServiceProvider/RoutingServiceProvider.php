<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser as FastRouteRouteParser;
use Psr\Http\Message\UriInterface;
use Venta\Contracts\Routing\FastrouteDispatcherFactory;
use Venta\Contracts\Routing\ImmutableRouteCollection as ImmutableRouteCollectionContract;
use Venta\Contracts\Routing\MiddlewarePipelineFactory as MiddlewarePipelineFactoryContract;
use Venta\Contracts\Routing\RequestRouteCollectionFactory as RequestRouteCollectionFactoryContract;
use Venta\Contracts\Routing\RouteCollection as RouteCollectionContract;
use Venta\Contracts\Routing\RouteDispatcherFactory as RouteDispatcherFactoryContract;
use Venta\Contracts\Routing\RouteGroup as RouteGroupContract;
use Venta\Contracts\Routing\RouteMatcher as RouteMatcherContract;
use Venta\Contracts\Routing\RouteParser as RouteParserContract;
use Venta\Contracts\Routing\RouteProcessor as RouteProcessorContract;
use Venta\Contracts\Routing\Router as RouterContract;
use Venta\Contracts\Routing\UrlGenerator as UrlGeneratorContract;
use Venta\Routing\Factory\GroupCountBasedDispatcherFactory;
use Venta\Routing\Factory\MiddlewarePipelineFactory;
use Venta\Routing\Factory\RequestRouteCollectionFactory;
use Venta\Routing\Factory\RouteDispatcherFactory;
use Venta\Routing\ProcessingRouteCollection;
use Venta\Routing\RouteCollection;
use Venta\Routing\RouteGroup;
use Venta\Routing\RouteMatcher;
use Venta\Routing\RouteParser;
use Venta\Routing\RoutePathProcessor;
use Venta\Routing\Router;
use Venta\Routing\UrlGenerator;
use Venta\ServiceProvider\AbstractServiceProvider;
use Zend\Diactoros\Uri;

/**
 * Class RoutingServiceProvider
 *
 * @package Venta\Framework\ServiceProvider
 */
final class RoutingServiceProvider extends AbstractServiceProvider
{

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->container()->bind(FastrouteDispatcherFactory::class, GroupCountBasedDispatcherFactory::class);
        $this->container()->bind(RequestRouteCollectionFactoryContract::class, RequestRouteCollectionFactory::class);
        $this->container()->bind(MiddlewarePipelineFactoryContract::class, MiddlewarePipelineFactory::class);
        $this->container()->bind(RouteCollectionContract::class, RouteCollection::class);
        $this->container()->bind(ImmutableRouteCollectionContract::class, RouteCollectionContract::class);
        $this->container()->bind(RouteDispatcherFactoryContract::class, RouteDispatcherFactory::class);
        $this->container()->bind(RouteMatcherContract::class, RouteMatcher::class);
        $this->container()->bind(RouteParserContract::class, RouteParser::class);
        $this->container()->bind(RouterContract::class, Router::class);
        $this->container()->bind(RouteProcessorContract::class, RoutePathProcessor::class);

        $this->container()->bind(UriInterface::class, Uri::class);
        $this->container()->bind(UrlGeneratorContract::class, UrlGenerator::class);

        $this->container()->bind(RouteGroupContract::class, RouteGroup::class);

        $this->container()->factory(RouteCollector::class, function () {
            return new RouteCollector(new FastRouteRouteParser\Std(), new GroupCountBased);
        }, true);

        $this->container()->decorate(RouteCollectionContract::class, ProcessingRouteCollection::class);
    }
}