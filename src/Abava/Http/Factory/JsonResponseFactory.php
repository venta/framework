<?php declare(strict_types = 1);

namespace Abava\Http\Factory;

use Abava\Http\Contract\JsonResponseFactory as JsonResponseFactoryContract;
use Abava\Http\Contract\Response as ResponseContract;
use Abava\Http\JsonResponse;
use Zend\Diactoros\Response\JsonResponse as BaseJsonResponse;

/**
 * Class JsonResponseFactory
 *
 * @package Abava\Http\Factory
 */
class JsonResponseFactory implements JsonResponseFactoryContract
{
    /**
     * Create a new JSON response.
     *
     * @param mixed $data Data to convert to JSON.
     * @param int $status Integer status code for the response; 200 by default.
     * @param array $headers Array of headers to use at initialization.
     * @param int $encodingOptions JSON encoding options to use.
     * @return ResponseContract
     */
    public function createResponse(
        $data,
        $status = 200,
        array $headers = [],
        $encodingOptions = BaseJsonResponse::DEFAULT_JSON_FLAGS
    ): ResponseContract
    {
        return new JsonResponse($data, $status, $headers, $encodingOptions);
    }
}