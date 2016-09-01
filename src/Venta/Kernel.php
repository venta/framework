<?php declare(strict_types = 1);

namespace Venta;

use Abava\Console\Contract\Collector as CommandCollector;
use Abava\Container\Contract\Container;
use Abava\Routing\Contract\Collector as RouteCollector;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollector;
use Dotenv\Dotenv;
use RuntimeException;
use Venta\Contract\ExtensionProvider\Bindings as BindingsProvider;
use Venta\Contract\ExtensionProvider\Commands as CommandsProvider;
use Venta\Contract\ExtensionProvider\Middlewares as MiddlewaresProvider;
use Venta\Contract\ExtensionProvider\Routes as RoutesProvider;

/**
 * Class Kernel
 *
 * @package Venta
 */
class Kernel implements \Venta\Contract\Kernel
{

    /**
     * DI Container instance
     *
     * @var Container
     */
    protected $container;

    /**
     * Array of defined extension providers
     *
     * @var array
     */
    protected $extensions = [];

    /**
     * File with array of extension providers
     *
     * @var string
     */
    protected $extensionsFile;

    /**
     * Project root absolute path
     *
     * @var string
     */
    protected $rootPath;

    /**
     * Version string holder
     *
     * @var string
     */
    protected $version = '0.0.1-hopefully-β-than-M2';

    /**
     * Kernel constructor.
     *
     * @param Container $container
     * @param string $rootPath
     * @param string $extensionsFile
     */
    public function __construct(
        Container $container,
        string $rootPath,
        string $extensionsFile = 'bootstrap/extensions.php'
    ) {
        $this->container = $container;
        $this->rootPath = $rootPath;
        $this->extensionsFile = $extensionsFile;

        /*
         * Binding basic singletons - container and kernel objects
         */
        $container->share(Container::class, $container, ['container']);
        $container->share(\Venta\Contract\Kernel::class, $this, ['kernel']);
    }

    /**
     * @inheritDoc
     */
    public function boot(): Container
    {
        /*
        * Load environment specific configuration from local .env file
        */
        (new Dotenv($this->rootPath))->load();

        /*
        * Find and load extension providers
        */
        $this->loadExtensionProviders();

        /*
        * Collect container bindings from extension providers
        */
        $this->collectBindings($this->container);

        /*
        * Collect routes from extension providers
        */
        $this->collectRoutes($this->container->get(RouteCollector::class));

        /*
        * Collect middlewares from extension providers
        */
        $this->collectMiddlewares($this->container->get(MiddlewareCollector::class));

        /*
        * Collect console commands from extension providers
        */
        $this->collectCommands($this->container->get(CommandCollector::class));

        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function getEnvironment(): string
    {
        return getenv('APP_ENV') ? getenv('APP_ENV') : 'local';
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @inheritDoc
     */
    public function isCli(): bool
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * Add extension provider to application
     *
     * @param string $provider
     */
    protected function addExtensionProvider(string $provider)
    {
        if (!isset($this->extensions[$provider])) {
            $this->extensions[$provider] = new $provider;
        }
    }

    /**
     * Collects extension providers' bindings
     *
     * @param Container $container
     * @return void
     */
    protected function collectBindings(Container $container)
    {
        foreach ($this->extensions as $provider) {
            if ($provider instanceof BindingsProvider) {
                $provider->bindings($container);
            }
        }
    }

    /**
     * Collects extension providers' commands
     *
     * @param CommandCollector $collector
     * @return void
     */
    protected function collectCommands(CommandCollector $collector)
    {
        foreach ($this->extensions as $provider) {
            if ($provider instanceof CommandsProvider) {
                $provider->commands($collector);
            }
        }
    }

    /**
     * Collects extension providers' middlewares
     *
     * @param MiddlewareCollector $collector
     * @return void
     */
    protected function collectMiddlewares(MiddlewareCollector $collector)
    {
        foreach ($this->extensions as $provider) {
            if ($provider instanceof MiddlewaresProvider) {
                $provider->middlewares($collector);
            }
        }
    }

    /**
     * Collects extension providers' routes
     *
     * @param RouteCollector $collector
     * @return void
     */
    protected function collectRoutes(RouteCollector $collector)
    {
        foreach ($this->extensions as $provider) {
            if ($provider instanceof RoutesProvider) {
                $collector->group('/', [$provider, 'routes']);
            }
        }
    }

    /**
     * Loads all extension providers from extensions file
     */
    protected function loadExtensionProviders()
    {
        $path = $this->rootPath . '/' . $this->extensionsFile;

        if (!file_exists($path)) {
            throw new RuntimeException(sprintf('Extensions file "%s" does not exist', $path));
        }
        if (!is_file($path)) {
            throw new RuntimeException(sprintf('Extensions file "%s" is not a regular file', $path));
        }
        if (!is_readable($path)) {
            throw new RuntimeException(sprintf('Extensions file "%s" is not readable', $path));
        }

        // requiring extension providers array
        $providers = require $path;

        if (!is_array($providers)) {
            throw new RuntimeException(sprintf('Extensions file "%s" must return array of class names', $path));
        }

        foreach ($providers as $provider) {
            $this->addExtensionProvider($provider);
        }
    }

}