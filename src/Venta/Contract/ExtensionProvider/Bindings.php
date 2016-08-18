<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Abava\Container\Contract\Container;

/**
 * Interface Bindings
 *
 * @package Venta\Contract\ExtensionProvider
 */
interface Bindings
{

    /**
     * Set bindings to provided application
     * or/and save application instance for later use
     *
     * @param Container $container
     * @return void
     */
    public function bindings(Container $container);

}