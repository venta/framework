<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Contracts\ApplicationContract;

/**
 * Interface TerminateContract
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface TerminateContract
{

    /**
     * Called after handling (dispatching) Http Request
     * or Console Input
     *
     * @param ApplicationContract $application
     * @return void
     */
    public function terminate(ApplicationContract $application);

}