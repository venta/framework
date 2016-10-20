<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Interface Delegate
 *
 * @package Venta\Contracts\Routing
 */
interface Delegate
{

    /**
     * Dispatch the next available middleware and return the response.
     *
     * @link https://github.com/php-fig/fig-standards/blob/master/proposed/http-middleware/middleware.md#25-psrhttpmiddlewaredelegateinterface
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function next(ServerRequestInterface $request): ResponseInterface;

}