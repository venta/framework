<?php declare(strict_types = 1);

namespace Venta\Contracts\Adr;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface Responder
 *
 * @package Venta\Contracts\Adr
 */
interface Responder
{

    /**
     * @param ServerRequestInterface $request
     * @param Payload|null $payload
     * @return ResponseInterface
     */
    public function run(ServerRequestInterface $request, Payload $payload = null): ResponseInterface;

}