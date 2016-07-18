<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Abava\Routing\RoutesCollector;

/**
 * Interface Routes
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface Routes
{

    /**
     * Add extension routes using routes collector
     *
     * @param RoutesCollector $routesCollector
     * @return void
     */
    public function routes(RoutesCollector $routesCollector);

}