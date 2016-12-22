<?php declare(strict_types = 1);


namespace Venta\Contracts\Config;

/**
 * Interface ConfigFileParser
 *
 * @package Venta\Contracts\Config
 */
interface ConfigFileParser
{
    /**
     * Parses configuration file.
     *
     * @param string $filename
     * @return array
     */
    public function parseFile(string $filename): array;

    /**
     * Returns an array of file extensions, that can be parsed with this parser.
     *
     * @return array
     */
    public function supportedExtensions(): array;
}