<?php declare(strict_types = 1);

namespace Venta\Contracts\Container;

use Interop\Container\ContainerInterface;

/**
 * Interface Container
 *
 * @package Venta\Contracts\Container
 */
interface Container extends ContainerInterface
{

    /**
     * Invoke a callable with resolving dependencies.
     *
     * @param $callable
     * @param array $arguments
     * @return mixed
     */
    public function call($callable, array $arguments = []);

    /**
     * {@inheritDoc}
     * @param array $arguments
     */
    public function get($id, array $arguments = []);

    /**
     * Defines, if passed in item is callable by container.
     *
     * @param  mixed $callable
     * @return bool
     */
    public function isCallable($callable): bool;
}