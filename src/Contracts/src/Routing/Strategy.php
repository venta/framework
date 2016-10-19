<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

use Psr\Http\Message\ResponseInterface;
use Venta\Routing\Route;

/**
 * Interface Strategy
 *
 * @package Venta\Contracts\Routing
 */
interface Strategy
{

    /**
     * Dispatches provided route:
     * - Makes controller and calls action
     * - Invokes callable
     * - Handles returned value
     * - Creates new Response instance for result value, if needed
     *
     * @param Route $route
     * @return ResponseInterface
     */
    public function dispatch(Route $route): ResponseInterface;

}