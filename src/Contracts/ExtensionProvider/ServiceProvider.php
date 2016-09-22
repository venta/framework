<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Contracts\Container\Container;

/**
 * Interface ServiceProvider
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface ServiceProvider
{

    /**
     * Set entries to DI container
     * or/and save container instance for later use
     *
     * @param Container $container
     * @return void
     */
    public function setServices(Container $container);

}