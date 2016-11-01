<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel;

use Venta\Contracts\Container\Container;
use Venta\Contracts\Kernel\Kernel;

/**
 * Class AbstractKernelBootstrap
 *
 * @package Venta\Framework\Kernel
 */
abstract class AbstractKernelBootstrap
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @var Kernel
     */
    protected $kernel;

    /**
     * AbstractKernelBootstrap constructor.
     *
     * @param Container $container
     * @param Kernel $kernel
     */
    public function __construct(Container $container, Kernel $kernel)
    {
        $this->container = $container;
        $this->kernel = $kernel;
    }

    /**
     * Bootstrap.
     *
     * @return void
     */
    abstract public function boot();
}