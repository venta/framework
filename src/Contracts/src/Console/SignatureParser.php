<?php declare(strict_types = 1);

namespace Venta\Contracts\Console;

/**
 * Interface SignatureParser
 *
 * @package Venta\Contracts\Console
 */
interface SignatureParser
{

    /**
     * Returns array with parsed signature data
     *
     * @param string $signature
     * @return array
     */
    public function parse(string $signature): array;

}