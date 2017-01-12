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
     * Register class name definition.
     *
     * @param string $id Contract (interface) name.
     * @param string $class Contract implementation class name.
     * @param bool $shared
     * @return void
     */
    public function bindClass(string $id, string $class, $shared = false);

    /**
     * Register callable factory definition.
     *
     * @param string $id
     * @param callable $callable
     * @param bool $shared
     * @return void
     */
    public function bindFactory(string $id, $callable, $shared = false);

    /**
     * Register concrete object.
     *
     * @param string $id
     * @param object $instance
     * @return void
     */
    public function bindInstance(string $id, $instance);

    /**
     * Invoke a callable with resolving dependencies.
     *
     * @param $callable
     * @param array $arguments
     * @return mixed
     */
    public function call($callable, array $arguments = []);

    /**
     * Decorates previous implementation.
     *
     * @param $id
     * @param callable $callback
     * @return void
     */
    public function decorate($id, callable $callback);

    /**
     * {@inheritDoc}
     * @param array $arguments
     */
    public function get($id, array $arguments = []);

    /**
     * Register method to be called after service instantiation.
     *
     * @param string $id Class or interface method to check against.
     * @param string $method Method name to call on service instance.
     * @param array $arguments
     * @return void
     */
    public function inflect(string $id, string $method, array $arguments = []);

    /**
     * Defines, if passed in item is callable by container.
     *
     * @param  mixed $callable
     * @return bool
     */
    public function isCallable($callable): bool;
}