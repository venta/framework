<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Config\Contract\Config;
use Venta\Config\Contract\Factory;

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
     * @param Factory $factory
     * @return Config
     */
    public function provideConfig(Factory $factory): Config;

}