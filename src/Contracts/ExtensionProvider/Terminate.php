<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

/**
 * Interface Terminate
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface Terminate
{

    /**
     * Called after handling (dispatching) Http Request
     * or Console Input
     *
     * @param Application $application
     * @return void
     */
    public function terminate(Application $application);

}