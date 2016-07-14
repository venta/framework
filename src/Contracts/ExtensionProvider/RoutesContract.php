<?php declare(strict_types = 1);

namespace Venta\Framework\Contracts\ExtensionProvider;

use Abava\Routing\RoutesCollector;

/**
 * Interface RoutesContract
 *
 * @package Venta\Framework\Contracts\ExtensionProvider
 */
interface RoutesContract
{

    /**
     * Add extension routes using routes collector
     *
     * @param RoutesCollector $routesCollector
     * @return void
     */
    public function routes(RoutesCollector $routesCollector);

}