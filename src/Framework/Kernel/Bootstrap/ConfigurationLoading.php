<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\Bootstrap;

use Symfony\Component\Finder\Finder;
use Venta\Config\Config;
use Venta\Contracts\Config\Config as ConfigContract;
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
        $config = [];
        $configFolder = $this->kernel()->rootPath() . '/config';
        foreach (Finder::create()->files()->name('*.php')->in($configFolder) as $file) {
            $config = array_merge_recursive($config, require $file->getPath());
        }

        $this->container()->bindInstance(ConfigContract::class, new Config($config));
    }
}