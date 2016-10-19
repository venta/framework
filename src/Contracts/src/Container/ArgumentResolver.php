<?php declare(strict_types = 1);

namespace Venta\Contracts\Container;

use Closure;
use ReflectionFunction;
use ReflectionFunctionAbstract;
use ReflectionMethod;

/**
 * Interface ArgumentResolver
 *
 * @package Venta\Contracts\Container
 */
interface ArgumentResolver extends ContainerAware
{
    /**
     * Create reflector depending on callable type.
     *
     * @param callable|string|array $callable
     * @return ReflectionFunction|ReflectionMethod|ReflectionFunctionAbstract
     */
    public function reflectCallable($callable): ReflectionFunctionAbstract;

    /**
     * Creates argument resolver closure for subject function.
     *
     * @param ReflectionFunctionAbstract $function
     * @return Closure
     */
    public function resolveArguments(ReflectionFunctionAbstract $function): Closure;

}