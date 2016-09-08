<?php declare(strict_types = 1);

namespace Abava\Config\Contract;

/**
 * Interface Parser
 *
 * @package Abava\Config\Contract
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