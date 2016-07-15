<?php declare(strict_types = 1);

namespace Venta\Contracts\Kernel;

/**
 * Interface AbstractKernelContract
 *
 * @package Venta
 */
interface AbstractKernelContract
{
    /**
     * Called on application termination
     *
     * @return void
     */
    public function terminate();
}