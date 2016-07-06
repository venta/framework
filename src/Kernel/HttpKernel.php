<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Venta\Framework\Application;
use Venta\Framework\Contracts\ApplicationContract;
use Venta\Framework\Contracts\Kernel\HttpKernelContract;
use Venta\Http\Contract\EmitterContract;
use Venta\Http\Contract\RequestContract;
use Venta\Http\Emitter;

/**
 * Class HttpKernel
 *
 * @package Venta\Framework
 */
class HttpKernel implements HttpKernelContract
{
    /**
     * Application instance holder
     *
     * @var ApplicationContract|Application
     */
    protected $application;

    /**
     * {@inheritdoc}
     */
    public function __construct(ApplicationContract $application)
    {
        $this->application = $application;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        // binding request instance
        if (!$this->application->has('request')) {
            $this->application->singleton('request', $request);
        }
        if (!$this->application->has(ServerRequestInterface::class) && $request instanceof ServerRequestInterface) {
            $this->application->singleton(ServerRequestInterface::class, $request);
        }
        if (!$this->application->has(RequestContract::class) && $request instanceof RequestContract) {
            $this->application->singleton(RequestContract::class, $request);
        }

        // binding response emitter
        if (!$this->application->has(EmitterContract::class)) {
            $this->application->singleton(EmitterContract::class, Emitter::class);
        }

        // calling ->bindings() on extension providers
        $this->application->bootExtensionProviders();

        /** @var \Venta\Routing\Router $router */
        $router = $this->application->make('router');
        $result = $router->dispatch($request);

        // bind the latest response instance, it may be used in terminate part
        if (!$this->application->has(ResponseInterface::class)) {
            $this->application->singleton(ResponseInterface::class, $result);
        }
        if (!$this->application->has('response')) {
            $this->application->singleton('response', $result);
        }

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function emit(ResponseInterface $response)
    {
        /** @var EmitterContract $emitter */
        $emitter = $this->application->make(EmitterContract::class);
        $emitter->emit($response);
    }


    /**
     * {@inheritdoc}
     */
    public function terminate(RequestInterface $request, ResponseInterface $response)
    {
        $this->application->terminate($request, $response);
    }

}