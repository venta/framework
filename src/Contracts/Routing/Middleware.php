<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

use Closure;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface Middleware
 *
 * @package Venta\Contracts\Routing
 */
interface Middleware
{
    /**
     * Function, called on middleware execution
     *
     * @param RequestInterface $request
     * @param Closure $next
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, Closure $next) : ResponseInterface;

}