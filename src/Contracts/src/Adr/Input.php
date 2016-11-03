<?php declare(strict_types = 1);

namespace Venta\Contracts\Adr;

use Psr\Http\Message\ServerRequestInterface;

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
     * @param ServerRequestInterface $request
     * @return array
     */
    public function process(ServerRequestInterface $request): array;

}