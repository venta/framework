<?php declare(strict_types = 1);

namespace Abava\Config\Contract;

/**
 * Interface Factory
 *
 * @package Abava\Config\Contract
 */
interface Factory
{

    /**
     * Creates config instance from file
     *
     * @param $filename
     * @return Config
     */
    public function fromFile($filename): Config;

}