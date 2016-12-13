<?php declare(strict_types = 1);

namespace Venta\Contracts\Http;

use Psr\Http\Message\ResponseInterface;
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
     * Creates new empty response.
     *
     * @param int $status Status code for the response.
     * @param array $headers Headers for the response.
     * @return ResponseInterface
     */
    public function createEmptyResponse(
        $status = 204,
        array $headers = []
    ): ResponseInterface;

    /**
     * Creates a new html response with text/html content-type header.
     *
     * @param string $html HTML or stream for the message body.
     * @param int $status Integer status code for the response; 200 by default.
     * @param array $headers Array of headers to use at initialization.
     * @return ResponseInterface
     */
    public function createHtmlResponse(
        string $html,
        int $status = 200,
        array $headers = []
    ): ResponseInterface;

    /**
     * Creates a new JSON response.
     *
     * @param mixed $data Data to convert to JSON.
     * @param int $status Integer status code for the response; 200 by default.
     * @param array $headers Array of headers to use at initialization.
     * @param int $encodingOptions JSON encoding options to use.
     * @return ResponseInterface
     */
    public function createJsonResponse(
        $data,
        int $status = 200,
        array $headers = [],
        int $encodingOptions = ResponseFactory::JSON_FLAG
    ): ResponseInterface;

    /**
     * Creates a new redirect response.
     *
     * @param string|UriInterface $uri URI for the Location header.
     * @param int $status Integer status code for the redirect; 302 by default.
     * @param array $headers Array of headers to use at initialization.
     * @return ResponseInterface
     */
    public function createRedirectResponse(
        $uri,
        int $status = 302,
        array $headers = []
    ): ResponseInterface;

    /**
     * Creates a new response.
     *
     * @param string|resource|StreamInterface $body Stream identifier and/or actual stream resource
     * @param int $status Status code for the response.
     * @param array $headers Headers for the response.
     * @return ResponseInterface
     */
    public function createResponse(
        $body = 'php://memory',
        int $status = 200,
        array $headers = []
    ): ResponseInterface;

    /**
     * Creates new text response.
     *
     * @param $text
     * @param int $status
     * @param array $headers
     * @return ResponseInterface
     */
    public function createTextResponse(
        $text,
        $status = 200,
        array $headers = []
    ): ResponseInterface;
}   