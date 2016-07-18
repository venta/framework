<?php declare(strict_types = 1);

namespace Venta\Kernel;

use Abava\Http\Contract\{
    Emitter as EmitterContract
};
use Abava\Http\Emitter;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Application;
use Venta\Contracts\Kernel\HttpKernel as HttpKernelContact;

/**
 * Class HttpKernel
 *
 * @package Venta
 */
class HttpKernel implements HttpKernelContact
{
    /**
     * Application instance holder
     *
     * @var Application
     */
    protected $application;

    /**
     * {@inheritdoc}
     */
    public function __construct(Application $application)
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
        if (!$this->application->has(RequestInterface::class) && $request instanceof RequestInterface) {
            $this->application->singleton(RequestInterface::class, $request);
        }
        if (!$this->application->has(ServerRequestInterface::class) && $request instanceof ServerRequestInterface) {
            $this->application->singleton(ServerRequestInterface::class, $request);
        }

        // binding response emitter
        if (!$this->application->has(EmitterContract::class)) {
            $this->application->singleton(EmitterContract::class, Emitter::class);
        }

        // calling ->bindings() on extension providers
        $this->application->bootExtensionProviders();

        /** @var \Abava\Routing\Router $router */
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
    public function terminate()
    {
        $this->application->terminate();
    }

}