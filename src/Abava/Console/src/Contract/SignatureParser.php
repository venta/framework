<?php declare(strict_types = 1);

namespace Abava\Console\Contract;

/**
 * Interface SignatureParser
 *
 * @package Abava\Console\Contract
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