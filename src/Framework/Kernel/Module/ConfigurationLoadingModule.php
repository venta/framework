<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\Module;

use Venta\Contracts\Config\Config;
use Venta\Contracts\Config\ConfigFactory;
use Venta\Framework\Kernel\AbstractKernelModule;

/**
 * Class ConfigurationLoadingModule
 *
 * @package Venta\Framework\Kernel\Module
 */
class ConfigurationLoadingModule extends AbstractKernelModule
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        /** @var ConfigFactory $configFactory */
        $configFactory = $this->container->get(ConfigFactory::class);

        // todo: implement arbitrary config files loading.
        $config = $configFactory->createFromFile($this->kernel->getRootPath() . '/config/app.php');

        $this->container->bindInstance(Config::class, $config);
    }
}