<?php declare(strict_types = 1);

namespace Venta\Contracts\Container;

/**
 * Interface MutableContainer
 *
 * @package Venta\Contracts\Container
 */
interface MutableContainer extends Container
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
     * @param string|object $service
     * @return void
     */
    public function bind(string $id, $service);

    /**
     * Register callable factory definition.
     *
     * @param string $id
     * @param callable $callable
     * @param bool $shared
     * @return void
     */
    public function factory(string $id, $callable, $shared = false);

}