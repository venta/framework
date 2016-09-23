<?php declare(strict_types = 1);


namespace Venta\Contracts\Container;

/**
 * Interface ArgumentResolverAware
 *
 * @package Venta\Contracts\Container
 */
interface ArgumentResolverAware
{
    /**
     * Set ArgumentResolver entity.
     *
     * @param ArgumentResolver $argumentResolver
     * @return mixed
     */
    public function setArgumentResolver(ArgumentResolver $argumentResolver);
}