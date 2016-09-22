<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Contracts\Config\Config;
use Venta\Contracts\Config\ConfigFactory;

/**
 * Interface ConfigProvider
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface ConfigProvider
{

    /**
     * Provides config, may use factory to load from file
     *
     * @param ConfigFactory $factory
     * @return Config
     */
    public function provideConfig(ConfigFactory $factory): Config;

}