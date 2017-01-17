<?php declare(strict_types = 1);

namespace Venta\Contracts\ServiceProvider;

use Venta\Contracts\Container\MutableContainer;

/**
 * Interface ServiceProvider.
 *
 * @package Venta\Contracts\ServiceProvider
 */
interface ServiceProvider
{

    /**
     * @param MutableContainer $container
     * @return void
     */
    public function bind(MutableContainer $container);

    /**
     * Boots the service provider.
     *
     * @return void
     */
    public function boot();

}