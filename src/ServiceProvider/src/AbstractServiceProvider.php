<?php declare(strict_types = 1);

namespace Venta\ServiceProvider;

use Venta\Contracts\Config\MutableConfig;
use Venta\Contracts\Console\CommandCollection;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Kernel\Kernel;
use Venta\Contracts\Routing\RouteCollection;
use Venta\Contracts\ServiceProvider\ServiceProvider;

/**
 * Class AbstractServiceProvider.
 *
 * @package Venta\ServiceProvider
 */
abstract class AbstractServiceProvider implements ServiceProvider
{

    /**
     * Application config.
     *
     * @var MutableConfig
     */
    private $config;

    /**
     * Container instance.
     *
     * @var Container
     */
    private $container;

    /**
     * AbstractServiceProvider constructor.
     *
     * @param Container $container
     * @param MutableConfig $mutableConfig
     */
    public function __construct(Container $container, MutableConfig $mutableConfig)
    {
        $this->container = $container;
        $this->config = $mutableConfig;
    }

    /**
     * @inheritdoc
     */
    public function boot()
    {
    }

    /**
     * @return MutableConfig
     */
    protected function config(): MutableConfig
    {
        return $this->config;
    }

    /**
     * @return Container
     */
    protected function container(): Container
    {
        return $this->container;
    }

    /**
     * @return Kernel
     */
    protected function kernel(): Kernel
    {
        return $this->container()->get(Kernel::class);
    }

    /**
     * Merges config params from service provider with the global configuration.
     *
     * @param string[] ...$configFiles
     */
    protected function loadConfigFromFiles(string ...$configFiles)
    {
        foreach ($configFiles as $configFile) {
            $this->config->merge(require $configFile);
        }
    }

    /**
     * Registers commands exposed by service provider.
     *
     * @param string[] ...$commandClasses
     */
    protected function provideCommands(string ...$commandClasses)
    {
        /** @var CommandCollection $commandCollector */
        $commandCollector = $this->container->get(CommandCollection::class);
        foreach ($commandClasses as $commandClass) {
            $commandCollector->add($commandClass);
        }
    }

    /**
     * @return RouteCollection
     */
    protected function routes(): RouteCollection
    {
        return $this->container()->get(RouteCollection::class);
    }
}