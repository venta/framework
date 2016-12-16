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
final class ConfigurationLoading extends AbstractKernelBootstrap
{
    /**
     * @inheritDoc
     */
    public function __invoke()
    {
        /** @var ConfigFactory $configFactory */
        $configFactory = $this->container()->get(ConfigFactory::class);

        // todo: implement arbitrary config files loading.
        $config = $configFactory->create([]);

        $this->container()->bindInstance(Config::class, $config);
    }
}