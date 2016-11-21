<?php declare(strict_types = 1);

namespace Venta\Adr;

use Psr\Http\Message\UriInterface;
use Venta\Contracts\Adr\Responder as ResponderContract;
use Venta\Contracts\Http\Response;
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
        $status = 200,
        array $headers = [],
        $encodingOptions = JsonResponse::DEFAULT_JSON_FLAGS
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
     * @param int $code
     * @return Response
     */
    protected function response($code = 200): Response
    {
        return $this->responseFactory->createResponse($code);
    }

}