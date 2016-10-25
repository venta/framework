<?php declare(strict_types = 1);

namespace Venta\Contracts\Kernel;

/**
 * Interface KernelModule
 *
 * @package Venta\Contracts\Kernel
 */
interface KernelModule
{
    /**
     * Initiates (loads) the module.
     *
     * @return void
     */
    public function init();
}
