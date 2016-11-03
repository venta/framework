<?php declare(strict_types = 1);

namespace Venta\Http\Factory;

use Venta\Contracts\Http\Response as ResponseContract;
use Venta\Contracts\Http\ResponseFactory as ResponseFactoryContract;
use Venta\Http\JsonResponse;
use Venta\Http\RedirectResponse;
use Venta\Http\Response;

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
        $status = 200,
        array $headers = [],
        $jsonFlag = ResponseFactoryContract::JSON_FLAG
    ): ResponseContract
    {
        return new JsonResponse($data, $status, $headers, $jsonFlag);
    }

    /**
     * {@inheritdoc}
     */
    public function createRedirectResponse($uri, $status = 302, array $headers = []): ResponseContract
    {
        return new RedirectResponse($uri, $status, $headers);
    }

    /**
     * {@inheritdoc}
     */
    public function createResponse($code = 200): ResponseContract
    {
        return (new Response)->withStatus($code);
    }
}