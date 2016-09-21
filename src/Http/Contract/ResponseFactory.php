<?php declare(strict_types = 1);

namespace Venta\Http\Contract;

/**
 * Interface ResponseFactory
 *
 * @package Venta\Http\Contracts
 */
interface ResponseFactory
{
    /**
     * Create a new response.
     *
     * @param integer $code HTTP status code
     * @return Response
     */
    public function createResponse($code = 200): Response;
}   