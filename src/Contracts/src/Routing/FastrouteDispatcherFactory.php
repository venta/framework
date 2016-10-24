<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

use FastRoute\Dispatcher;

/**
 * Interface FastrouteDispatcherFactory
 *
 * @package Venta\Contracts\Routing
 */
interface FastrouteDispatcherFactory
{

    /**
     * Creates dispatcher with provided parsed route data.
     *
     * @param array $data
     * @return Dispatcher
     */
    public function create(array $data): Dispatcher;

}