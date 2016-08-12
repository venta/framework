<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Abava\Routing\Contract\Group;

/**
 * Interface Routes
 *
 * @package Venta\Contract\ExtensionProvider
 */
interface Routes
{

    /**
     * Add extension routes using routes collector
     *
     * @param Group $collector
     * @return void
     */
    public function routes(Group $collector);

}