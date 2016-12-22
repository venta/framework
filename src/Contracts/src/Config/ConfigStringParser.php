<?php declare(strict_types = 1);

namespace Venta\Contracts\Config;

/**
 * Interface ConfigStringParser
 *
 * @package Venta\Contracts\Config
 */
interface ConfigStringParser
{
    /**
     * Parses configuration string.
     *
     * @param string $configuration
     * @return array
     */
    public function parseString(string $configuration): array;
}