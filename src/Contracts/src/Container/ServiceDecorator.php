<?php declare(strict_types = 1);

namespace Venta\Contracts\Container;

/**
 * Interface ServiceDecorator
 *
 * @package Venta\Contracts\Container
 */
interface ServiceDecorator
{

    /**
     *
     * @param string $id Service id to decorate.
     * @param callable|string $decorator Class name or callback to decorate with.
     * @return void
     */
    public function add(string $id, $decorator);

    /**
     *
     * @param string $id
     * @param $object
     * @param bool $once
     * @return mixed
     */
    public function apply(string $id, $object, bool $once = false);

}