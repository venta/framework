<?php declare(strict_types = 1);

namespace Venta\Framework;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Venta\Container\Container;
use Venta\Framework\Contracts\ApplicationContract;
use Venta\Routing\Response;

/**
 * Class Application
 *
 * @package Venta\Framework
 */
abstract class Application extends Container implements ApplicationContract
{
    /**
     * Array of defined extension providers
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * Version string holder
     *
     * @var string
     */
    protected $version = '0.0.1-wp';

    /**
     * Root path
     *
     * @var string
     */
    protected $root;

    /**
     * File with array of extension providers
     *
     * @var string
     */
    protected $extensionsFile;

    /**
     * Construct function
     *
     * @param string $root
     * @param string $extensionsFile
     */
    public function __construct(string $root, string $extensionsFile = 'bootstrap/extensions.php')
    {
        $this->extensionsFile = $extensionsFile;
        $this->root = $root;

        $this->configure();
    }

    /**
     * {@inheritdoc}
     */
    public function version(): string
    {
        return $this->version;
    }

    /**
     * {@inheritdoc}
     */
    public function isCli(): bool
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * {@inheritdoc}
     */
    abstract public function configure();

    /**
     * {@inheritdoc}
     */
    public function run(RequestInterface $request): ResponseInterface
    {
        $this->singleton(RequestInterface::class, $request);
        $this->singleton(ResponseInterface::class, $response = new Response);

        $this->singleton('response', ResponseInterface::class);
        $this->singleton('request', RequestInterface::class);

        $this->loadExtensionProviders();
        $this->callExtensionProvidersMethod('bindings', $this);

        /** @var \Venta\Routing\Router $router */
        $router = $this->make('router');
        return $router->dispatch($request->getMethod(), $request->getUri()->getPath());
    }

    /**
     * {@inheritdoc}
     */
    public function emit(ResponseInterface $response)
    {
        /** @var \Zend\Diactoros\Response\EmitterInterface $emitter */
        $emitter = $this->make(\Zend\Diactoros\Response\EmitterInterface::class);
        $emitter->emit($response);
    }

    /**
     * {@inheritdoc}
     */
    public function terminate(RequestInterface $request, ResponseInterface $response)
    {
        $this->callExtensionProvidersMethod('terminate', $this, $request, $response);
    }

    /**
     * Add extension provider to application
     *
     * @param string $provider
     */
    public function addExtensionProvider(string $provider)
    {
        if (!isset($this->extensions[$provider])) {
            $this->extensions[$provider] = new $provider;
        }
    }

    /**
     * Calls passed in method on all extension providers
     *
     * @param string $method
     * @param array  $arguments
     */
    protected function callExtensionProvidersMethod(string $method, ...$arguments)
    {
        foreach ($this->extensions as $provider) {
            if (is_callable([$provider, $method])) {
                $provider->$method(...$arguments);
            }
        }
    }

    /**
     * Loads all extension providers from file
     */
    protected function loadExtensionProviders()
    {
        $path = $this->root . '/' . $this->extensionsFile;

        if (file_exists($path) && is_file($path) && is_readable($path)) {
            $providers = require $path;
            $providers = is_array($providers) ? $providers : [];

            foreach ($providers as $provider) {
                $this->addExtensionProvider($provider);
            }
        }
    }
}