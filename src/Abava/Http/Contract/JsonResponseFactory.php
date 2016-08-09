<?php declare(strict_types = 1);

namespace Abava\Http\Contract;

use Zend\Diactoros\Response\JsonResponse;

/**
 * Interface JsonResponseFactory
 *
 * @package Abava\Http\Contract
 */
interface JsonResponseFactory
{
    /**
     * Create a new JSON response.
     *
     * @param mixed $data Data to convert to JSON.
     * @param int $status Integer status code for the response; 200 by default.
     * @param array $headers Array of headers to use at initialization.
     * @param int $encodingOptions JSON encoding options to use.
     * @return Response
     */
    public function createJsonResponse(
        $data,
        $status = 200,
        array $headers = [],
        $encodingOptions = JsonResponse::DEFAULT_JSON_FLAGS): Response;
}