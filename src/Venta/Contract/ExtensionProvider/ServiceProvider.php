<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Abava\Container\Contract\Container;

/**
 * Interface ServiceProvider
 *
 * @package Venta\Contract\ExtensionProvider
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