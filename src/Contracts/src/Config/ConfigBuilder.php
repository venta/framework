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
     * Add configuration parser to parsers collection.
     *
     * @param ConfigFileParser $parser
     * @return void
     */
    public function addFileParser(ConfigFileParser $parser);

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
     * @return void
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
     * @param string $path
     * @param mixed  $value
     * @return void
     */
    public function push(string $path, $value);

    /**
     * Sets value to the configuration data.
     *
     * @param string $path
     * @param mixed  $value
     * @return void
     */
    public function set(string $path, $value);
}