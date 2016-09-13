<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Abava\Routing\Contract\Group;

/**
 * Interface RouteProvider
 *
 * @package Venta\Contract\ExtensionProvider
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