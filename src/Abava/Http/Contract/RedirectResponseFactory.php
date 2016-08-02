<?php declare(strict_types = 1);

namespace Abava\Http\Contract;

/**
 * Interface RedirectResponseFactory
 *
 * @package Abava\Http\Contract
 */
interface RedirectResponseFactory
{
    /**
     * Create a new redirect response.
     *
     * @param $uri
     * @param int $status
     * @param array $headers
     * @return Response
     */
    public function createResponse($uri, $status = 302, array $headers = []): Response;
}