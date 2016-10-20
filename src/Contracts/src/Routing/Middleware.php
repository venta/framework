<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface Middleware
 *
 * @package Venta\Contracts\Routing
 */
interface Middleware
{
    /**
     * Process a server request and return a response.
     *
     * Takes the incoming request and optionally modifies it before delegating
     * to the next frame to get a response.
     *
     * @link https://github.com/php-fig/fig-standards/blob/master/proposed/http-middleware/middleware.md#24-psrhttpmiddlewareservermiddlewareinterface
     *
     * @param ServerRequestInterface $request
     * @param Delegate $delegate
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, Delegate $delegate): ResponseInterface;

}