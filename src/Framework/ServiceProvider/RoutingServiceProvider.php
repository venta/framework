<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use FastRoute\DataGenerator\GroupCountBased;
use FastRoute\RouteCollector;
use FastRoute\RouteParser as FastRouteRouteParser;
use Psr\Http\Message\UriInterface;
use Venta\Contracts\Routing\FastrouteDispatcherFactory;
use Venta\Contracts\Routing\MiddlewarePipelineFactory as MiddlewarePipelineFactoryContract;
use Venta\Contracts\Routing\MutableRouteCollection as MutableRouteCollectionContract;
use Venta\Contracts\Routing\RequestRouteCollectionFactory as RequestRouteCollectionFactoryContract;
use Venta\Contracts\Routing\Route as RouteContract;
use Venta\Contracts\Routing\RouteCollection as RouteCollectionContract;
use Venta\Contracts\Routing\RouteDispatcherFactory as RouteDispatcherFactoryContract;
use Venta\Contracts\Routing\RouteGroup as RouteGroupContract;
use Venta\Contracts\Routing\RouteMatcher as RouteMatcherContract;
use Venta\Contracts\Routing\RouteParser as RouteParserContract;
use Venta\Contracts\Routing\Router as RouterContract;
use Venta\Contracts\Routing\UrlGenerator as UrlGeneratorContract;
use Venta\Routing\AliasedPathPatternRouteCollection;
use Venta\Routing\Factory\GroupCountBasedDispatcherFactory;
use Venta\Routing\Factory\MiddlewarePipelineFactory;
use Venta\Routing\Factory\RequestRouteCollectionFactory;
use Venta\Routing\Factory\RouteDispatcherFactory;
use Venta\Routing\Route;
use Venta\Routing\RouteCollection;
use Venta\Routing\RouteGroup;
use Venta\Routing\RouteMatcher;
use Venta\Routing\RouteParser;
use Venta\Routing\Router;
use Venta\Routing\UrlGenerator;
use Venta\ServiceProvider\AbstractServiceProvider;
use Zend\Diactoros\Uri;

/**
 * Class RoutingServiceProvider
 *
 * @package Venta\Framework\ServiceProvider
 */
class RoutingServiceProvider extends AbstractServiceProvider
{

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->container->bindClass(FastrouteDispatcherFactory::class, GroupCountBasedDispatcherFactory::class, true);
        $this->container->bindClass(
            RequestRouteCollectionFactoryContract::class,
            RequestRouteCollectionFactory::class,
            true
        );
        $this->container->bindClass(MiddlewarePipelineFactoryContract::class, MiddlewarePipelineFactory::class, true);
        $this->container->bindClass(MutableRouteCollectionContract::class, RouteCollection::class, true);
        $this->container->bindClass(RouteCollectionContract::class, MutableRouteCollectionContract::class, true);
        $this->container->bindClass(RouteDispatcherFactoryContract::class, RouteDispatcherFactory::class, true);
        $this->container->bindClass(RouteMatcherContract::class, RouteMatcher::class, true);
        $this->container->bindClass(RouteParserContract::class, RouteParser::class, true);
        $this->container->bindClass(RouterContract::class, Router::class, true);

        $this->container->bindClass(UriInterface::class, Uri::class, true);
        $this->container->bindClass(UrlGeneratorContract::class, UrlGenerator::class, true);

        $this->container->bindClass(RouteGroupContract::class, RouteGroup::class);
        $this->container->bindClass(RouteContract::class, Route::class);

        $this->container->bindFactory(RouteCollector::class, function () {
            return new RouteCollector(new FastRouteRouteParser\Std(), new GroupCountBased);
        }, true);

        $this->container->decorate(MutableRouteCollectionContract::class, function ($routes) {
            return new AliasedPathPatternRouteCollection($routes);
        });
    }
}