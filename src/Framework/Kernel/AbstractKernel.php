<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel;

use InvalidArgumentException;
use Psr\Log\LoggerAwareInterface;
use Venta\Config\ConfigFactory;
use Venta\Contracts\Config\Config;
use Venta\Contracts\Config\ConfigFactory as ConfigFactoryContract;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Container\ContainerAware;
use Venta\Contracts\Kernel\Kernel;
use Venta\Contracts\ServiceProvider\ServiceProvider;
use Venta\Framework\Kernel\Module\ConfigurationLoadingModule;
use Venta\Framework\Kernel\Module\EnvironmentDetectionModule;
use Venta\Framework\Kernel\Module\ErrorHandlingModule;
use Venta\Framework\Kernel\Resolver\ServiceProviderDependencyResolver;
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
    protected $containerClass = \Venta\Container\Container::class;

    /**
     * @inheritDoc
     */
    public function boot(): Container
    {
        $container = $this->initServiceContainer();

        foreach ($this->registerKernelModules() as $kernelModuleClass) {
            $this->initKernelModule($kernelModuleClass, $container);
        }

        /** @var Config $config */
        $config = $container->get(Config::class);

        // Here we boot service providers on by one. The correct order is ensured be resolver.
        $resolver = $container->get(ServiceProviderDependencyResolver::class);
        foreach ($resolver->resolve($this->registerServiceProviders()) as $providerClass) {
            $this->bootServiceProvider($providerClass, $container, $config);
        }

        // When all service providers have been booted
        // we can be sure that all possible config changes were already made.
        // At this point we lock the configuration to prevent any changes during application run.
        $config->lock();

        return $container;
    }

    /**
     * @inheritDoc
     */
    public function getEnvironment(): string
    {
        return getenv('APP_ENV') ?: 'local';
    }

    /**
     * @return string
     */
    abstract public function getRootPath(): string;

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return self::VERSION;
    }

    /**
     * @inheritDoc
     */
    public function isCli(): bool
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * Boots service provider with base config.
     *
     * @param string $providerClass
     * @param Container $container
     * @param Config $baseConfig
     * @throws InvalidArgumentException
     */
    protected function bootServiceProvider(string $providerClass, Container $container, Config $baseConfig)
    {
        // Ensure service provider implements contract.
        if (!is_subclass_of($providerClass, AbstractServiceProvider::class)) {
            throw new InvalidArgumentException(
                sprintf('Class "%s" must be a subclass of "%s".',
                    $providerClass, AbstractServiceProvider::class
                )
            );
        }

        // Instantiate and boot service provider.
        /** @var ServiceProvider $provider */
        $provider = new $providerClass($container, $baseConfig);
        $provider->boot();

        // todo: dispatch event
    }

    /**
     * Initializes kernel module by class name.
     * This is the ultimate point where kernel module functionality is enabled.
     *
     * @param string $kernelModuleClass
     * @param Container $container
     * @throws InvalidArgumentException
     */
    protected function initKernelModule(string $kernelModuleClass, Container $container)
    {
        if (!is_subclass_of($kernelModuleClass, AbstractKernelModule::class)) {
            throw new InvalidArgumentException(
                sprintf('Class "%s" must be a subclass of "%s".',
                    $kernelModuleClass, AbstractKernelModule::class
                )
            );
        }

        /** @var AbstractKernelModule $module */
        $module = new $kernelModuleClass($container, $this);
        $module->init();

        // todo: dispatch event
    }

    /**
     * Registers kernel modules.
     * This is a main place to define primary kernel functionality.
     * Be careful changing this method, it may cause kernel failure.
     *
     * @return array
     */
    protected function registerKernelModules(): array
    {
        $modules = [
            EnvironmentDetectionModule::class,
            ConfigurationLoadingModule::class,
            ErrorHandlingModule::class,
        ];

        // Here we can add environment dependant modules
        //if ($this->getEnvironment() === \Venta\Contracts\Kernel\Kernel::ENV_LOCAL) {
        //    $modules[] = 'KernelModule';
        //}

        return $modules;
    }

    /**
     * Returns a list of all registered service providers.
     *
     * @return string[]
     */
    abstract protected function registerServiceProviders(): array;

    /**
     * Initializes service container.
     */
    private function initServiceContainer()
    {
        /** @var Container $container */
        $container = new $this->containerClass;

        $container->bindInstance(Container::class, $container);
        $container->bindInstance(Kernel::class, $this);

        $container->bindClass(ConfigFactoryContract::class, ConfigFactory::class, true);

        // Register default inflections.
        $container->addInflection(ContainerAware::class, 'setContainer', ['container' => $container]);
        $container->addInflection(LoggerAwareInterface::class, 'setLogger');

        return $container;
    }
}