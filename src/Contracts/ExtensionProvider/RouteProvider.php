<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Routing\Contract\Group;

/**
 * Interface RouteProvider
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface RouteProvider
{

    /**
     * Add extension routes using routes collector
     *
     * @param Group $collector
     * @return void
     */
    public function provideRoutes(Group $collector);

}