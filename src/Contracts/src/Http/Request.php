<?php declare(strict_types = 1);

namespace Venta\Contracts\Http;

use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Routing\Route;

/**
 * Interface Request
 *
 * @package Venta\Contracts\Http
 */
interface Request extends ServerRequestInterface
{

    /**
     * Get current route.
     *
     * @return Route
     */
    public function getRoute(): Route;

    /**
     * Add route to the request.
     *
     * @param Route $route
     * @return Request
     */
    public function withRoute(Route $route): Request;

}