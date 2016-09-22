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
     * Creates config instance from file
     *
     * @param $filename
     * @return Config
     */
    public function fromFile($filename): Config;

}