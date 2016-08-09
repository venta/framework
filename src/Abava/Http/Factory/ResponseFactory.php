<?php declare(strict_types = 1);

namespace Abava\Http\Factory;

use Abava\Http\Contract\JsonResponseFactory as JsonResponseFactoryContract;
use Abava\Http\Contract\RedirectResponseFactory as RedirectResponseFactoryContract;
use Abava\Http\Contract\Response as ResponseContract;
use Abava\Http\Contract\ResponseFactory as ResponseFactoryContract;
use Abava\Http\JsonResponse;
use Abava\Http\RedirectResponse;
use Abava\Http\Response;
use Zend\Diactoros\Response\JsonResponse as BaseJsonResponse;

/**
 * Class ResponseFactory
 *
 * @package Abava\Http\Factory
 */
class ResponseFactory implements
    ResponseFactoryContract,
    RedirectResponseFactoryContract,
    JsonResponseFactoryContract
{
    /**
     * {@inheritdoc}
     */
    public function createResponse($code = 200): ResponseContract
    {
        return (new Response)->withStatus($code);
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
    public function createJsonResponse(
        $data,
        $status = 200,
        array $headers = [],
        $encodingOptions = BaseJsonResponse::DEFAULT_JSON_FLAGS
    ): ResponseContract
    {
        return new JsonResponse($data, $status, $headers, $encodingOptions);
    }
}