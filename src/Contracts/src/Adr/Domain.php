<?php declare(strict_types = 1);

namespace Venta\Contracts\Adr;

/**
 * Interface Domain
 *
 * @package Venta\Contracts\Adr
 */
interface Domain
{

    /**
     * @param $input
     * @return Payload
     */
    public function handle($input): Payload;

}