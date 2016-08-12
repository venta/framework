<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Venta\Contract\Application;

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
     * @param Application $application
     * @return void
     */
    public function bindings(Application $application);

}