<?php declare(strict_types = 1);

namespace Venta\Http\Contract;

/**
 * Interface RedirectResponseFactory
 *
 * @package Venta\Http\Contracts
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
    public function createRedirectResponse($uri, $status = 302, array $headers = []): Response;
}