<?php declare(strict_types = 1);

namespace Venta\Http\Factory;

use Venta\Contracts\Http\Response as ResponseContract;
use Venta\Contracts\Http\ResponseFactory as ResponseFactoryContract;
use Venta\Http\Response;
use Zend\Diactoros\Response as ZendDiactorosResponse;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\RedirectResponse;

/**
 * Class ResponseFactory
 *
 * @package Venta\Http\Factory
 */
class ResponseFactory implements ResponseFactoryContract
{
    /**
     * @inheritDoc
     */
    public function createHtmlResponse(string $html, int $code = 200, array $headers = []): ResponseContract
    {
        return new Response(new HtmlResponse($html, $code, $headers));
    }

    /**
     * {@inheritdoc}
     */
    public function createJsonResponse(
        $data,
        int $status = 200,
        array $headers = [],
        int $jsonFlag = ResponseFactoryContract::JSON_FLAG
    ): ResponseContract
    {
        return new Response(new JsonResponse($data, $status, $headers, $jsonFlag));
    }

    /**
     * {@inheritdoc}
     */
    public function createRedirectResponse($uri, int $status = 302, array $headers = []): ResponseContract
    {
        return new Response(new RedirectResponse($uri, $status, $headers));
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse($bodyStream = 'php://memory', int $code = 200, array $headers = []): ResponseContract
    {
        return new Response(new ZendDiactorosResponse($bodyStream, $code, $headers));
    }

}