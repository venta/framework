<?php declare(strict_types = 1);

namespace Venta\Contracts\Config;

/**
 * Interface ConfigFactory
 *
 * @package Venta\Contracts\Config
 */
interface ConfigFactory
{

    /**
     * Creates config instance.
     *
     * @param array $data
     * @return Config
     */
    public function create(array $data): Config;

}