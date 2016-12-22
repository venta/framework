<?php declare(strict_types = 1);

namespace Venta\Contracts\Config;

/**
 * Interface MutableConfig
 *
 * @package Venta\Contracts\Config
 */
interface MutableConfig extends Config
{

    /**
     * Merges configuration data.
     *
     * @param array $config
     * @return void
     */
    public function merge(array $config);

    /**
     * Appends a value to a config array.
     *
     * @param string $path
     * @param mixed $value
     * @return void
     */
    public function push(string $path, $value);

    /**
     * Sets value to the configuration data.
     *
     * @param string $path
     * @param mixed $value
     * @return void
     */
    public function set(string $path, $value);

}