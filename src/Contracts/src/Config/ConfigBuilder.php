<?php declare(strict_types = 1);


namespace Venta\Contracts\Config;

/**
 * Interface ConfigBuilder
 *
 * @package Venta\Contracts\Config
 */
interface ConfigBuilder
{
    /**
     * Builds Config instance with collected values.
     *
     * @return Config
     */
    public function build(): Config;

    /**
     * Merges configuration data.
     *
     * @param array $config
     * @return Config
     */
    public function merge(array $config);

    /**
     * Merges configuration data form file.
     *
     * @param string $filename
     * @return void
     */
    public function mergeFile(string $filename);

    /**
     * Appends a value to a config array.
     *
     * @param $path
     * @param $value
     * @return void
     */
    public function push(string $path, $value);

    /**
     * Sets value to the configuration data.
     *
     * @param string $path
     * @param $value
     * @return void
     */
    public function set(string $path, $value);
}