<?php declare(strict_types = 1);

namespace Venta\Http\Factory;

use Venta\Contracts\Http\JsonResponseFactory as JsonResponseFactoryContract;
use Venta\Contracts\Http\RedirectResponseFactory as RedirectResponseFactoryContract;
use Venta\Contracts\Http\Response as ResponseContract;
use Venta\Contracts\Http\ResponseFactory as ResponseFactoryContract;
use Venta\Http\JsonResponse;
use Venta\Http\RedirectResponse;
use Venta\Http\Response;
use Zend\Diactoros\Response\JsonResponse as BaseJsonResponse;

/**
 * Class ResponseFactory
 *
 * @package Venta\Http\Factory
 */
class ResponseFactory implements
    ResponseFactoryContract,
    RedirectResponseFactoryContract,
    JsonResponseFactoryContract
{
    /**
     * {@inheritdoc}
     */
    public function createJsonResponse(
        $data,
        $status = 200,
        array $headers = [],
        $encodingOptions = BaseJsonResponse::DEFAULT_JSON_FLAGS
    ): ResponseContract
    {
        return new JsonResponse($data, $status, $headers, $encodingOptions);
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