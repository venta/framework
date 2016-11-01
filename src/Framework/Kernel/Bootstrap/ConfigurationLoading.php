<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\Bootstrap;

use Venta\Contracts\Config\Config;
use Venta\Contracts\Config\ConfigFactory;
use Venta\Framework\Kernel\AbstractKernelBootstrap;

/**
 * Class ConfigurationLoading
 *
 * @package Venta\Framework\Kernel\Bootstrap
 */
class ConfigurationLoading extends AbstractKernelBootstrap
{
    /**
     * @inheritDoc
     */
    public function boot()
    {
        /** @var ConfigFactory $configFactory */
        $configFactory = $this->container->get(ConfigFactory::class);

        // todo: implement arbitrary config files loading.
        $config = $configFactory->createFromFile($this->kernel->getRootPath() . '/config/app.php');

        $this->container->bindInstance(Config::class, $config);
    }
}