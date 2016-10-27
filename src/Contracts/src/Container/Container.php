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
     */
    public function call($callable, array $arguments = []);

    /**
     * {@inheritDoc}
     * @param array $arguments
     */
    public function get($id, array $arguments = []);

    /**
     * Register method to be called after service instantiation.
     *
     * @param string $id
     * @param string $method
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

    /**
     * Add an service to the container. It allows to assign multiple aliases to resolve a single definition.
     *
     * @param string $id Container service identifier.
     * @param mixed $service Container service definition.
     */
    public function set(string $id, $service);

    /**
     * Add shared instance to container.
     *
     * @param string $id Container service identifier.
     * @param mixed $service
     */
    public function share(string $id, $service);
}