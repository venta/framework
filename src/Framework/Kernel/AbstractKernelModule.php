<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel;

use Venta\Contracts\Container\Container;
use Venta\Contracts\Kernel\Kernel as KernelContract;
use Venta\Contracts\Kernel\KernelModule;

/**
 * Class AbstractModule
 *
 * @package Venta\Framework\Kernel
 */
abstract class AbstractKernelModule implements KernelModule
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var KernelContract
     */
    protected $kernel;

    /**
     * AbstractKernelModule constructor.
     *
     * @param Container $container
     * @param KernelContract $kernel
     */
    public function __construct(Container $container, KernelContract $kernel)
    {
        $this->container = $container;
        $this->kernel = $kernel;
    }


    /**
     * @inheritDoc
     */
    abstract public function init();
}