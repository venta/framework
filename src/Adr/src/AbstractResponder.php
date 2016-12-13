<?php declare(strict_types = 1);

namespace Venta\Adr;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;
use Venta\Contracts\Adr\Responder as ResponderContract;
use Venta\Contracts\Http\ResponseFactory;
use Venta\Contracts\Http\ResponseFactoryAware;
use Zend\Diactoros\Response\JsonResponse;

/**
 * Class AbstractResponder
 *
 * @package Venta\Adr
 */
abstract class AbstractResponder implements ResponderContract, ResponseFactoryAware
{
    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * @inheritDoc
     */
    public function setResponseFactory(ResponseFactory $factory)
    {
        $this->responseFactory = $factory;
    }

    protected function empty($status = 204, array $headers = [])
    {
        return $this->responseFactory->createEmptyResponse($status, $headers);
    }

    /**
     * Creates html response.
     *
     * @param string $html
     * @param int $status
     * @param array $headers
     * @return ResponseInterface
     */
    protected function html(string $html, int $status = 200, array $headers = []): ResponseInterface
    {
        return $this->responseFactory->createHtmlResponse($html, $status, $headers);
    }

    /**
     * Creates HTTP response with JSON content type header and JSON encoded $data.
     *
     * @param mixed $data Data to convert to JSON.
     * @param int $status Integer status code for the response; 200 by default.
     * @param array $headers Array of headers to use at initialization.
     * @param int $jsonFlag JSON encoding options to use.
     * @return ResponseInterface
     */
    protected function json(
        $data,
        int $status = 200,
        array $headers = [],
        int $encodingOptions = JsonResponse::DEFAULT_JSON_FLAGS
    ): ResponseInterface {
        return $this->responseFactory->createJsonResponse($data, $status, $headers, $encodingOptions);
    }

    /**
     * Creates HTTP redirect response with $uri used as Location header.
     *
     * @param string|UriInterface $uri
     * @param int $status
     * @param array $headers
     * @return ResponseInterface
     */
    protected function redirect($uri, $status = 302, array $headers = []): ResponseInterface
    {
        return $this->responseFactory->createRedirectResponse($uri, $status, $headers);
    }

    /**
     * Creates HTTP response.
     *
     * @param string $bodyStream Stream to use as response body.
     * @param int $status
     * @param array $headers
     * @return ResponseInterface
     */
    protected function response($bodyStream = 'php://memory', int $status = 200, array $headers = []): ResponseInterface
    {
        return $this->responseFactory->createResponse($bodyStream, $status, $headers);
    }
}