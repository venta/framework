<?php declare(strict_types = 1);

namespace Venta\Framework\Contracts\ExtensionProvider;

use Venta\Framework\Contracts\ApplicationContract;

/**
 * Interface TerminateContract
 *
 * @package Venta\Framework\Contracts\ExtensionProvider
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