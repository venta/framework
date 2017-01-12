<?php declare(strict_types = 1);

namespace Venta\Contracts\Container;

/**
 * Interface ServiceRegistry
 *
 * @package Venta\Contracts\Container
 */
interface ServiceRegistry
{
    /**
     * Decorates previous implementation.
     *
     * @param string $id
     * @param callable|string $decorator Class name or callback to decorate with.
     * @return void
     */
    public function addDecorator(string $id, $decorator);

    /**
     * Register method to be called after service instantiation.
     *
     * @param string $id Class or interface method to check against.
     * @param string $method Method name to call on service instance.
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

}