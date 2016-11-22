<?php declare(strict_types = 1);

namespace Venta\Contracts\Adr;

use Venta\Contracts\Http\Request;

/**
 * Interface Input
 *
 * @package Venta\Contracts\Adr
 */
interface Input
{

    /**
     * Converts request to an array of arguments for Domain handler.
     *
     * @param Request $request
     * @return array
     */
    public function process(Request $request): array;

}