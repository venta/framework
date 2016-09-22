<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface MiddlewarePipeline
 *
 * @package Venta\Contracts\Routing
 */
interface MiddlewarePipeline
{

    /**
     * Process middleware pipeline:
     * - add $last as the last middleware
     * - pass $request instance through pipeline
     * - expect Response instance on the end
     *
     * @param RequestInterface $request
     * @param callable $last
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, callable $last): ResponseInterface;

}