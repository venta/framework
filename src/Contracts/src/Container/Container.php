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
     * Register callable factory definition.
     *
     * @param string $id
     * @param callable $callable
     * @param bool $shared
     * @return
     */
    public function factory(string $id, $callable, $shared = false);

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
     * Register concrete object.
     *
     * @param string $id
     * @param $instance
     */
    public function instance(string $id, $instance);

    /**
     * Defines, if passed in item is callable by container.
     *
     * @param  mixed $callable
     * @return bool
     */
    public function isCallable($callable): bool;

    /**
     * Register class name definition.
     *
     * @param string $id Container service identifier.
     * @param string $service Container service definition.
     * @param bool $shared
     * @return
     * @internal param bool $share
     */
    public function set(string $id, string $service, $shared = false);
}