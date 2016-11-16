<?php declare(strict_types = 1);

namespace Venta\Contracts\Http;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Interface ResponseFactory
 *
 * @package Venta\Contracts\Http
 */
interface ResponseFactory
{
    /**
     * Default JSON encoding flag for json response.
     */
    const JSON_FLAG = JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES;

    /**
     * Create a new JSON response.
     *
     * @param mixed $data Data to convert to JSON.
     * @param int $status Integer status code for the response; 200 by default.
     * @param array $headers Array of headers to use at initialization.
     * @param int $jsonFlag JSON encoding options to use.
     * @return Response
     */
    public function createJsonResponse(
        $data,
        int $status = 200,
        array $headers = [],
        int $jsonFlag = ResponseFactory::JSON_FLAG
    ): Response;

    /**
     * Create a new redirect response.
     *
     * @param string|UriInterface $uri
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function createRedirectResponse($uri, int $status = 302, array $headers = []): Response;

    /**
     * Create a new response.
     *
     * @param int $code HTTP status code
     * @param string|resource|StreamInterface $bodyStream Stream to use as body.
     * @return Response
     */
    public function createResponse(int $code = 200, $bodyStream = 'php://memory'): Response;
}   