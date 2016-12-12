<?php declare(strict_types = 1);


namespace Venta\Contracts\Config;

/**
 * Interface ConfigFileParser
 *
 * @package Venta\Contracts\Config
 */
interface ConfigFileParser extends ConfigParser
{
    /**
     * Parses configuration file.
     *
     * @param string $filename
     * @return array
     */
    public function fromFile(string $filename): array;
}