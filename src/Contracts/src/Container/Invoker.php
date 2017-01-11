<?php declare(strict_types = 1);

namespace Venta\Contracts\Container;

use Venta\Container\Invokable;

/**
 * Interface Invoker
 *
 * @package Venta\Contracts\Container
 */
interface Invoker
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
     * @param Invokable $invokable
     * @param array $arguments
     * @return mixed
     */
    public function invoke(Invokable $invokable, array $arguments = []);

    /**
     * Defines, if passed in item is callable by container.
     *
     * @param  mixed $callable
     * @return bool
     */
    public function isCallable($callable): bool;

}