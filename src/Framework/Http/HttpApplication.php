<?php declare(strict_types = 1);

namespace Venta\Framework\Http;

use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Http\HttpApplication as HttpApplicationContract;
use Venta\Contracts\Http\ResponseEmitter as EmitterContract;
use Venta\Contracts\Kernel\Kernel;
use Venta\Contracts\Routing\MiddlewarePipelineFactory;
use Venta\Routing\Router;

/**
 * Class HttpApplication
 *
 * @package Venta\Framework\Http
 */
class HttpApplication implements HttpApplicationContract
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * HttpApplication constructor.
     *
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * @inheritDoc
     */
    public function run(ServerRequestInterface $request)
    {
        $this->kernel->boot();

        $this->container = $this->kernel->getContainer();

        /** @var MiddlewarePipelineFactory $factory */
        $factory = $this->container->get(MiddlewarePipelineFactory::class);
        $pipeline = $factory->create($this->container->get('config')->get('middlewares', []));
        /** @var Router $delegate */
        $delegate = $this->container->get(Router::class);
        $response = $pipeline->process($request, $delegate);

        /** @var EmitterContract $emitter */
        $emitter = $this->container->get(EmitterContract::class);
        $emitter->emit($response);
    }

}