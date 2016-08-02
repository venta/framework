<?php declare(strict_types = 1);

namespace Abava\Http\Factory;

use Abava\Http\Contract\RedirectResponseFactory as RedirectResponseFactoryContract;
use Abava\Http\Contract\Response as ResponseContract;
use Abava\Http\RedirectResponse;
use Psr\Http\Message\UriInterface;

/**
 * Class RedirectResponseFactory
 *
 * @package Abava\Http\Factory
 */
class RedirectResponseFactory implements RedirectResponseFactoryContract
{
    /**
     * Create a new redirect response.
     *
     * @param string|UriInterface $uri URI for the Location header.
     * @param int $status Integer status code for the redirect; 302 by default.
     * @param array $headers Array of headers to use at initialization.
     * @return ResponseContract
     */
    public function createResponse($uri, $status = 302, array $headers = []): ResponseContract
    {
        return new RedirectResponse($uri, $status, $headers);
    }
}