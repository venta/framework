<?php declare(strict_types = 1);

namespace Venta\Contracts\Http;

/**
 * Interface ResponseFactory
 *
 * @package Venta\Contracts\Http
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