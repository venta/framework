<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\Bootstrap;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use Venta\Config\ConfigBuilder;
use Venta\Config\ConfigFactory;
use Venta\Config\Parser\Json;
use Venta\Contracts\Config\Config as ConfigContract;
use Venta\Contracts\Config\ConfigBuilder as ConfigBuilderContract;
use Venta\Contracts\Config\ConfigFactory as ConfigFactoryContract;
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
        $this->container()->bindClass(ConfigFactoryContract::class, ConfigFactory::class, true);
        $this->container()->bindFactory(ConfigBuilderContract::class, function () {
            $builder = new ConfigBuilder();
            $builder->addFileParser(new Json(new Filesystem(new Local($this->kernel()->rootPath() . '/config'))));

            return $builder;
        }, true);
    }
}