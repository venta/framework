<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Venta\Framework\Application;
use Venta\Framework\Contracts\ApplicationContract;
use Venta\Framework\Contracts\Kernel\Http\EmitterContract;
use Venta\Framework\Contracts\Kernel\HttpKernelContract;
use Venta\Framework\Http\Response;

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

    // todo Can HttpKernel create Request by itself ?

    /**
     * {@inheritdoc}
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        // todo Should we check if it was (re-)binded before ?
        // binding request
        $this->application->singleton(RequestInterface::class, $request);
        $this->application->singleton('request', RequestInterface::class);

        // todo Should we check if it was (re-)binded before ?
        // binding response
        $this->application->singleton(ResponseInterface::class, $response = new Response());
        $this->application->singleton('response', ResponseInterface::class);
        // binding response emitter
        $this->application->singleton(\Venta\Framework\Contracts\Kernel\Http\EmitterContract::class, \Zend\Diactoros\Response\SapiEmitter::class);

        $this->application->bootExtensionProviders();

        /** @var \Venta\Routing\Router $router */
        $router = $this->application->make('router');
        /*
         * Todo: Would be lovely if we could do this:
         *
         * $result = $router->dispatch($request);
         * $this->application->bind(Response, $result);
         * return $result;
         *
         * To save latest response right before emitting and application->terminate call.
         */
        return $router->dispatch($request);
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