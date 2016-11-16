<?php declare(strict_types = 1);

namespace Venta\Http\Factory;

use Venta\Contracts\Http\Response as ResponseContract;
use Venta\Contracts\Http\ResponseFactory as ResponseFactoryContract;
use Venta\Http\Response;
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
    public function createResponse(int $code = 200): ResponseContract
    {
        return new Response(new \Zend\Diactoros\Response('php://memory', $code));
    }
}