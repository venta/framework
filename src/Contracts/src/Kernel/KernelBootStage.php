<?php declare(strict_types = 1);

namespace Venta\Contracts\Kernel;

use Venta\Contracts\Container\Container;

/**
 * Interface KernelBootStage
 *
 * @package Venta\Contracts\Kernel
 */
interface KernelBootStage
{
    /**
     * Runs kernel boot stage.
     *
     * @param Container $container
     * @return void
     */
    public function run(Container $container);
}
