<?php declare(strict_types = 1);

namespace Venta\Http;

use Psr\Http\Message\ResponseInterface;
use Venta\Contracts\Http\ResponseFactory as ResponseFactoryContract;
use Zend\Diactoros\Response;
use Zend\Diactoros\Response\EmptyResponse;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Diactoros\Response\TextResponse;

/**
 * Class ResponseFactory
 *
 * @package Venta\Http
 */
class ResponseFactory implements ResponseFactoryContract
{
    /**
     * @inheritDoc
     */
    public function createEmptyResponse($status = 204, array $headers = []): ResponseInterface
    {
        return new EmptyResponse($status, $headers);
    }

    /**
     * @inheritDoc
     */
    public function createHtmlResponse(string $html, int $status = 200, array $headers = []): ResponseInterface
    {
        return new HtmlResponse($html, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function createJsonResponse(
        $data,
        int $status = 200,
        array $headers = [],
        int $encodingOptions = ResponseFactoryContract::JSON_FLAG
    ): ResponseInterface {

        return new JsonResponse($data, $status, $headers, $encodingOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function createRedirectResponse($uri, int $status = 302, array $headers = []): ResponseInterface
    {
        return new RedirectResponse($uri, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse($body = 'php://memory', int $status = 200, array $headers = []): ResponseInterface
    {
        return new Response($body, $status, $headers);
    }

    /**
     * @inheritDoc
     */
    public function createTextResponse($text, $status = 200, array $headers = []): ResponseInterface
    {
        return new TextResponse($text, $status, $headers);
    }
}