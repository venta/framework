<?php declare(strict_types = 1);

namespace Venta\Contracts\Adr;

use Venta\Contracts\Http\Request;
use Venta\Contracts\Http\Response;

/**
 * Interface Responder
 *
 * @package Venta\Contracts\Adr
 */
interface Responder
{

    /**
     * @param Request $request
     * @param Payload|null $payload
     * @return Response
     */
    public function run(Request $request, Payload $payload = null): Response;

}