<?php declare(strict_types = 1);

namespace Venta\Adr;

use Psr\Http\Message\UriInterface;
use Venta\Contracts\Adr\Responder as ResponderContract;
use Venta\Contracts\Http\Response;
use Venta\Contracts\Http\ResponseFactory;
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
     * Responder constructor.
     *
     * @param ResponseFactory $responseFactory
     */
    public function __construct(ResponseFactory $responseFactory)
    {
        $this->responseFactory = $responseFactory;
    }

    /**
     * Creates html response.
     *
     * @param string $html
     * @param int $code
     * @param array $headers
     * @return Response
     */
    protected function html(string $html, int $code, array $headers = [])
    {
        return $this->responseFactory->createHtmlResponse($html, $code, $headers);
    }

    /**
     * Creates http response with JSON content type header and JSON encoded $data.
     *
     * @param $data
     * @param int $status
     * @param array $headers
     * @param int $encodingOptions
     * @return Response
     */
    protected function json(
        $data,
        int $status = 200,
        array $headers = [],
        int $encodingOptions = JsonResponse::DEFAULT_JSON_FLAGS
    ): Response
    {
        return $this->responseFactory->createJsonResponse($data, $status, $headers, $encodingOptions);
    }

    /**
     * Creates redirect http response with $uri used as Location header.
     *
     * @param string|UriInterface $uri
     * @param int $status
     * @param array $headers
     * @return Response
     */
    protected function redirect($uri, $status = 302, array $headers = []): Response
    {
        return $this->responseFactory->createRedirectResponse($uri, $status, $headers);
    }

    /**
     * Creates http response.
     *
     * @param string $bodyStream Stream to use as response body.
     * @param int $code
     * @param array $headers
     * @return Response
     */
    protected function response($bodyStream = 'php://memory', int $code = 200, array $headers = []): Response
    {
        return $this->responseFactory->createResponse($bodyStream, $code, $headers);
    }

}