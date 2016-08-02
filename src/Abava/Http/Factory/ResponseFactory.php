<?php declare(strict_types = 1);

namespace Abava\Http\Factory;

use Abava\Http\Contract\Response as ResponseContract;
use Abava\Http\Contract\ResponseFactory as ResponseFactoryContract;
use Abava\Http\Response;

/**
 * Class ResponseFactory
 *
 * @package Abava\Http\Factory
 */
class ResponseFactory implements ResponseFactoryContract
{
    /**
     * Create a new response.
     *
     * @param integer $code HTTP status code
     * @return ResponseContract
     */
    public function createResponse($code = 200): ResponseContract
    {
        return (new Response)->withStatus($code);
    }
}