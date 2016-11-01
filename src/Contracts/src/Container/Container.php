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
     * Register method to be called after service instantiation.
     *
     * @param string $id
     * @param string $method
     * @param array $arguments
     * @return void
     */
    public function addInflection(string $id, string $method, array $arguments = []);

    /**
     * Register class name definition.
     *
     * @param string $id Contract (interface) name.
     * @param string $class Contract implementation class name.
     * @param bool $shared
     */
    public function bindClass(string $id, string $class, $shared = false);

    /**
     * Register callable factory definition.
     *
     * @param string $id
     * @param callable $callable
     * @param bool $shared
     */
    public function bindFactory(string $id, $callable, $shared = false);

    /**
     * Register concrete object.
     *
     * @param string $id
     * @param object $instance
     */
    public function bindInstance(string $id, $instance);

    /**
     * Invoke a callable with resolving dependencies.
     *
     * @param $callable
     * @param array $arguments
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