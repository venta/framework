<?php declare(strict_types = 1);

namespace Venta\Contracts\Container;

/**
 * Interface ContainerAware
 *
 * @package Venta\Contracts\Container
 */
interface ContainerAware
{
    /**
     * Set container entity.
     *
     * @param Container $container
     * @return ContainerAware
     */
    public function setContainer(Container $container);
}