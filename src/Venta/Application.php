<?php declare(strict_types = 1);

namespace Venta;

use Abava\Container\Container;
use Abava\Http\Factory\RequestFactory;
use Abava\Http\Factory\ResponseFactory;
use Abava\Routing\Contract\Collector as RouteCollector;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollector;
use Dotenv\Dotenv;
use Psr\Http\Message\ServerRequestInterface;
use Venta\Contract\Application as ApplicationContact;
use Venta\Contract\ExtensionProvider\Commands as CommandsProvider;
use Venta\Contract\ExtensionProvider\Middlewares as MiddlewareProvider;
use Venta\Contract\ExtensionProvider\Routes as RouteProvider;

/**
 * Class Application
 *
 * @package Venta
 */
abstract class Application extends Container implements ApplicationContact
{
    /**
     * Constants, defining env application know about
     */
    const ENV_LOCAL = 'local';
    const ENV_STAGE = 'stage';
    const ENV_LIVE = 'live';
    const ENV_TEST = 'test';

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
     * self::bootExtensionProviders called flag
     *
     * @var bool
     */
    protected $booted = false;

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

        (new Dotenv($root))->load();

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
    public function environment(): string
    {
        return getenv('APP_ENV') ? getenv('APP_ENV') : static::ENV_LOCAL;
    }

    /**
     * {@inheritdoc}
     */
    public function isLiveEnvironment(): bool
    {
        return $this->environment() === static::ENV_LIVE;
    }

    /**
     * {@inheritdoc}
     */
    public function isLocalEnvironment(): bool
    {
        return $this->environment() === static::ENV_LOCAL;
    }

    /**
     * {@inheritdoc}
     */
    public function isStageEnvironment(): bool
    {
        return $this->environment() === static::ENV_STAGE;
    }

    /**
     * {@inheritdoc}
     */
    public function isTestEnvironment(): bool
    {
        return $this->environment() === static::ENV_TEST;
    }

    /**
     * Whether self::bootExtensionProviders was called
     *
     * @return bool
     */
    public function isBooted()
    {
        return $this->booted;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function configure();

    /**
     * {@inheritdoc}
     */
    public function terminate()
    {
        $this->callExtensionProvidersMethod('terminate', $this);
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
     * {@inheritdoc}
     */
    public function bootExtensionProviders()
    {
        if (!$this->isBooted()) {
            $this->loadExtensionProviders();
            $this->callExtensionProvidersMethod('bindings', $this);
            // todo Make this deferred, we don't need error handlers until we got error
            $this->callExtensionProvidersMethod('errors', $this->get('error_handler'));
            $this->booted = true;
        }
    }

    /**
     * Calls passed in method on all extension providers
     *
     * @param string $method
     * @param array $arguments
     */
    protected function callExtensionProvidersMethod(string $method, ...$arguments)
    {
        foreach ($this->extensions as $provider) {
            // todo Check for concrete interface implementation
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

    /**
     * Create a request from the supplied superglobal values.
     *
     * @return ServerRequestInterface
     */
    protected function createServerRequest(): ServerRequestInterface
    {
        return $this->createServerRequestFactory()->createServerRequestFromGlobals();
    }

    /**
     * todo: specify return as PSR-16 server request factory interface
     *
     * @return RequestFactory
     */
    protected function createServerRequestFactory()
    {
        return new RequestFactory;
    }

    /**
     * todo: specify return as PSR-16 server request factory interface
     *
     * @return ResponseFactory
     */
    protected function createResponseFactory()
    {
        return new ResponseFactory;
    }

    /**
     * Bind default implementations to contracts
     *
     * @return void
     */
    protected function bindRouting()
    {
        // binding response emitter
        if (!$this->has(\Abava\Http\Contract\Emitter::class)) {
            $this->singleton(\Abava\Http\Contract\Emitter::class, \Abava\Http\Emitter::class);
        }

        // binding route path parser
        if (!$this->has(\FastRoute\RouteParser::class)) {
            $this->bind(\FastRoute\RouteParser::class, \Abava\Routing\Parser::class);
        }

        // binding route parameter parser
        if (!$this->has(\FastRoute\DataGenerator::class)) {
            $this->bind(\FastRoute\DataGenerator::class, \FastRoute\DataGenerator\GroupCountBased::class);
        }

        // binding route collector
        if (!$this->has(\Abava\Routing\Contract\Collector::class)) {
            $this->bind(\Abava\Routing\Contract\Collector::class, \Abava\Routing\Collector::class);
        }

        // binding url generator
        if (!$this->has(\Abava\Routing\Contract\UrlGenerator::class)) {
            $this->bind(\Abava\Routing\Contract\UrlGenerator::class, \Abava\Routing\Collector::class);
        }

        // binding middleware collector
        if (!$this->has(\Abava\Routing\Contract\Middleware\Collector::class)) {
            $this->singleton(
                \Abava\Routing\Contract\Middleware\Collector::class,
                \Abava\Routing\Middleware\Collector::class
            );
        }

        // binding middleware pipeline
        if (!$this->has(\Abava\Routing\Contract\Middleware\Pipeline::class)) {
            $this->bind(
                \Abava\Routing\Contract\Middleware\Pipeline::class,
                \Abava\Routing\Middleware\Pipeline::class
            );
        }

        // binding dispatcher (via dispatcher factory)
        if (!$this->has(\Abava\Routing\Contract\Dispatcher\Factory::class)) {
            $this->bind(
                \Abava\Routing\Contract\Dispatcher\Factory::class,
                \Abava\Routing\Dispatcher\Factory\GroupCountBasedFactory::class
            );
        }

        // binging route matcher
        if (!$this->has(\Abava\Routing\Contract\Matcher::class)) {
            $this->bind(\Abava\Routing\Contract\Matcher::class, \Abava\Routing\Matcher::class);
        }

        // binding dispatch strategy
        if (!$this->has(\Abava\Routing\Contract\Strategy::class)) {
            $this->bind(\Abava\Routing\Contract\Strategy::class, \Abava\Routing\Strategy\Generic::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function routes(RouteCollector $collector)
    {
        foreach ($this->extensions as $provider) {
            if ($provider instanceof RouteProvider) {
                $collector->group('/', [$provider, 'routes']);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function middlewares(MiddlewareCollector $collector)
    {
        foreach ($this->extensions as $provider) {
            if ($provider instanceof MiddlewareProvider) {
                $provider->middlewares($collector);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function commands(\Symfony\Component\Console\Application $console)
    {
        foreach ($this->extensions as $provider) {
            if ($provider instanceof CommandsProvider) {
                $provider->commands($console);
            }
        }
    }

}