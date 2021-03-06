<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel;

use InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Venta\Config\ConfigProxy;
use Venta\Config\MutableConfig;
use Venta\Container\ContainerProxy;
use Venta\Contracts\Config\Config;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Container\ContainerAware;
use Venta\Contracts\Container\MutableContainer;
use Venta\Contracts\Http\ResponseFactoryAware;
use Venta\Contracts\Kernel\Kernel;
use Venta\Contracts\ServiceProvider\ServiceProvider;
use Venta\Framework\Kernel\Bootstrap\ConfigurationLoading;
use Venta\Framework\Kernel\Bootstrap\EnvironmentDetection;
use Venta\Framework\Kernel\Bootstrap\ErrorHandling;
use Venta\Framework\Kernel\Bootstrap\Logging;
use Venta\ServiceProvider\AbstractServiceProvider;

/**
 * Class AbstractKernel
 *
 * @package Venta\Framework\Kernel
 */
abstract class AbstractKernel implements Kernel
{
    const VERSION = '0.1.0';

    /**
     * Service container class name.
     *
     * @var string
     */
    protected $containerClass = \Venta\Container\MutableContainer::class;

    /**
     * @var ServiceProvider[]
     */
    private $providers = [];

    /**
     * @inheritDoc
     */
    public function boot(): Container
    {
        $container = $this->initServiceContainer();

        foreach ($this->getBootstraps() as $bootstrapClass) {
            $this->invokeBootstrap($bootstrapClass, $container);
        }

        $appConfig = $container->get(Config::class)->all();
        $config = new MutableConfig($appConfig);
        $container->bind(Config::class, new ConfigProxy($config));

        // Here we initializing service providers and collect container bindings.
        foreach ($this->registerServiceProviders() as $providerClass) {
            $this->ensureServiceProvider($providerClass);
            /** @var AbstractServiceProvider $provider */
            $this->providers[] = $provider = new $providerClass($container->get(Container::class), $config);
            $provider->bind($container);
            $config->merge($appConfig);
        }

        // Booting service providers on by one allows
        foreach ($this->providers as $provider) {
            $provider->boot();
            $config->merge($appConfig);
        }

        return $container->get(Container::class);
    }

    /**
     * @inheritDoc
     */
    public function environment(): string
    {
        return getenv('APP_ENV') ?: 'local';
    }

    /**
     * @inheritDoc
     */
    public function isCli(): bool
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * @return string
     */
    abstract public function rootPath(): string;

    /**
     * @inheritDoc
     */
    public function version(): string
    {
        return self::VERSION;
    }

    /**
     * Returns list of kernel bootstraps.
     * This is a main place to tune default kernel behavior.
     * Change carefully, as it may cause kernel failure.
     *
     * @return string[]
     */
    protected function getBootstraps(): array
    {
        $modules = [
            EnvironmentDetection::class,
            ConfigurationLoading::class,
            Logging::class,
            ErrorHandling::class,
        ];

        // Here we can add environment dependant modules.
        //if ($this->getEnvironment() === \Venta\Contracts\Kernel\Kernel::ENV_LOCAL) {
        //    $modules[] = 'KernelModule';
        //}

        return $modules;
    }

    /**
     * Invokes kernel bootstrap.
     * This is the point where specific kernel functionality defined by bootstrap is enabled.
     *
     * @param string $bootstrapClass
     * @param MutableContainer $container
     * @throws InvalidArgumentException
     */
    protected function invokeBootstrap(string $bootstrapClass, MutableContainer $container)
    {
        $this->ensureBootstrap($bootstrapClass);

        (new $bootstrapClass($container, $this))();
    }

    /**
     * Returns a list of all registered service providers.
     *
     * @return string[]
     */
    abstract protected function registerServiceProviders(): array;

    /**
     * Adds default service inflections.
     *
     * @param MutableContainer $container
     */
    private function addDefaultInflections(MutableContainer $container)
    {
        $container->inflect(ContainerAware::class, 'setContainer');
        $container->inflect(LoggerAwareInterface::class, 'setLogger');
        $container->inflect(ResponseFactoryAware::class, 'setResponseFactory');
    }

    /**
     * Binds default services to container.
     *
     * @param MutableContainer $container
     */
    private function bindDefaultServices(MutableContainer $container)
    {
        $container->bind(Container::class, new ContainerProxy($container));
        $container->bind(Kernel::class, $this);
    }

    /**
     * Ensures bootstrap class extends abstract kernel bootstrap.
     *
     * @param string $bootstrapClass
     * @throws InvalidArgumentException
     */
    private function ensureBootstrap(string $bootstrapClass)
    {
        if (!is_subclass_of($bootstrapClass, AbstractKernelBootstrap::class)) {
            throw new InvalidArgumentException(
                sprintf('Class "%s" must be a subclass of "%s".', $bootstrapClass, AbstractKernelBootstrap::class)
            );
        }
    }

    /**
     * Ensures service provider implements contract.
     *
     * @param string $providerClass
     * @throws InvalidArgumentException
     */
    private function ensureServiceProvider(string $providerClass)
    {
        if (!is_subclass_of($providerClass, AbstractServiceProvider::class)) {
            throw new InvalidArgumentException(
                sprintf('Class "%s" must be a subclass of "%s".', $providerClass, AbstractServiceProvider::class)
            );
        }
    }

    /**
     * Initializes service container.
     */
    private function initServiceContainer(): MutableContainer
    {
        /** @var MutableContainer $container */
        $container = new $this->containerClass;

        $this->bindDefaultServices($container);
        $this->addDefaultInflections($container);

        return $container;
    }
}