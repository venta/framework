<?php declare(strict_types = 1);

namespace Venta\Framework\Contracts\Kernel;

use Venta\Framework\Contracts\ApplicationContract;

/**
 * Interface AbstractKernelContract
 *
 * @package Venta\Framework
 */
interface AbstractKernelContract
{
    /**
     * Construct function
     *
     * @param ApplicationContract $application
     */
    public function __construct(ApplicationContract $application);

    /**
     * Called on application termination
     *
     * @return void
     */
    public function terminate();
}