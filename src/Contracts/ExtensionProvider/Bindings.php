<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Contracts\Application;

/**
 * Interface Bindings
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface Bindings
{

    /**
     * Set bindings to provided application
     * or/and save application instance for later use
     *
     * @param Application $application
     * @return void
     */
    public function bindings(Application $application);

}