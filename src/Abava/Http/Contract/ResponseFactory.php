<?php declare(strict_types = 1);

namespace Abava\Http\Contract;

/**
 * Interface ResponseFactory
 *
 * @package Abava\Http\Contract
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