<?php declare(strict_types = 1);

namespace Venta\Contracts\Container;

use ReflectionFunctionAbstract;

/**
 * Interface ArgumentResolver
 *
 * @package Venta\Contracts\Container
 */
interface ArgumentResolver
{

    /**
     * Resolves function arguments and replaces them with provided values.
     *
     * @param ReflectionFunctionAbstract $function
     * @param array $arguments
     * @return array
     */
    public function resolve(ReflectionFunctionAbstract $function, array $arguments = []): array;

}