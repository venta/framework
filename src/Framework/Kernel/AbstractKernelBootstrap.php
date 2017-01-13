<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel;

use Venta\Contracts\Container\MutableContainer;
use Venta\Contracts\Kernel\Kernel;

/**
 * Class AbstractKernelBootstrap
 *
 * @package Venta\Framework\Kernel
 */
abstract class AbstractKernelBootstrap
{
    /**
     * @var MutableContainer
     */
    private $container;

    /**
     * @var Kernel
     */
    private $kernel;

    /**
     * AbstractKernelBootstrap constructor.
     *
     * @param MutableContainer $container
     * @param Kernel $kernel
     */
    public function __construct(MutableContainer $container, Kernel $kernel)
    {
        $this->container = $container;
        $this->kernel = $kernel;
    }

    /**
     * Runs the Bootstrap.
     *
     * @return void
     */
    abstract public function __invoke();

    /**
     * @return MutableContainer
     */
    protected function container(): MutableContainer
    {
        return $this->container;
    }

    /**
     * @return Kernel
     */
    protected function kernel(): Kernel
    {
        return $this->kernel;
    }
}