<?php declare(strict_types = 1);

namespace Venta\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Config\Config;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Kernel\Kernel;
use Venta\Contracts\Routing\Delegate;
use Venta\Contracts\Routing\MiddlewarePipelineFactory;
use Venta\Contracts\Routing\Router;


/**
 * Class HttpApplication
 *
 * @package Venta\Framework\Http
 */
final class HttpApplication implements Delegate
{

    /**
     * @var Container
     */
    private $container;

    /**
     * HttpApplication constructor.
     *
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->container = $kernel->boot();
    }

    /**
     * Returns service container instance.
     *
     * @return Container
     */
    public function container(): Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function next(ServerRequestInterface $request): ResponseInterface
    {
        return $this->run($request);
    }

    /**
     * @inheritDoc
     */
    public function run(ServerRequestInterface $request)
    {
        /** @var MiddlewarePipelineFactory $factory */
        $factory = $this->container->get(MiddlewarePipelineFactory::class);
        $middlewares = $this->container->get(Config::class)->get('middlewares', []);
        $pipeline = $factory->create($middlewares);
        /** @var Router $router */
        $router = $this->container->get(Router::class);

        return $pipeline->process($request, $router);
    }

}