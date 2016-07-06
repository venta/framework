<?php declare(strict_types = 1);

namespace Venta\Framework;

use Dotenv\Dotenv;
use Venta\Container\Container;
use Venta\Framework\Contracts\ApplicationContract;

/**
 * Class Application
 *
 * @package Venta\Framework
 */
abstract class Application extends Container implements ApplicationContract
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
            $this->booted = true;
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