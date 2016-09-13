<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Abava\Config\Contract\Config;
use Abava\Config\Contract\Factory;

/**
 * Interface ConfigProvider
 *
 * @package Venta\Contract\ExtensionProvider
 */
interface ConfigProvider
{

    /**
     * Provides config, may use factory to load from file
     *
     * @param Factory $factory
     * @return Config
     */
    public function provideConfig(Factory $factory): Config;

}