<?php declare(strict_types = 1);

namespace Venta\Contracts\Config;

/**
 * Interface Parser
 *
 * @package Venta\Contracts\Config
 */
interface Parser
{

    /**
     * Parses configuration string into Config object
     *
     * @param string $configuration
     * @return Config
     */
    public function parse(string $configuration): Config;

}