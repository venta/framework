<?php declare(strict_types = 1);

namespace Venta\Contracts\Container;

use Closure;
use ReflectionFunctionAbstract;

/**
 * Interface ArgumentResolver
 *
 * @package Venta\Contracts\Container
 */
interface ArgumentResolver
{

    /**
     * Creates argument resolving closure for subject function.
     *
     * @param ReflectionFunctionAbstract $function
     * @return Closure
     */
    public function createCallback(ReflectionFunctionAbstract $function): Closure;

    /**
     * Resolves function arguments and replaces them with provided values.
     *
     * @param ReflectionFunctionAbstract $function
     * @param array $arguments
     * @return array
     */
    public function resolve(ReflectionFunctionAbstract $function, array $arguments = []): array;

}