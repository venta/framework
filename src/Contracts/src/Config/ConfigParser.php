<?php declare(strict_types = 1);

namespace Venta\Contracts\Config;

/**
 * Interface ConfigParser
 *
 * @package Venta\Contracts\Config
 */
interface ConfigParser
{
    /**
     * Parses configuration string.
     *
     * @param string $configuration
     * @return array
     */
    public function fromString(string $configuration): array;

    /**
     * Returns an array of file extensions, that can be parsed with this parser.
     *
     * @return array
     */
    public function supportedExtensions(): array;
}