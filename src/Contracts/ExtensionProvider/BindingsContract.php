<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Contracts\ApplicationContract;

/**
 * Class BindingsContract
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface BindingsContract
{

    /**
     * Set bindings to provided application
     * or/and save application instance for later use
     *
     * @param ApplicationContract $application
     * @return void
     */
    public function bindings(ApplicationContract $application);

}