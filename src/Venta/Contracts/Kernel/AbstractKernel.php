<?php declare(strict_types = 1);

namespace Venta\Contracts\Kernel;

/**
 * Interface AbstractKernel
 *
 * @package Venta
 */
interface AbstractKernel
{
    /**
     * Called on application termination
     *
     * @return void
     */
    public function terminate();
}