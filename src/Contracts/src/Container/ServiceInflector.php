<?php declare(strict_types = 1);

namespace Venta\Contracts\Container;

/**
 * Interface ServiceInflector
 *
 * @package Venta\Contracts\Container
 */
interface ServiceInflector
{
    /**
     * Add new object inflection to be applied.
     *
     * @param string $id
     * @param string $method
     * @param array $arguments
     * @return void
     */
    public function add(string $id, string $method, array $arguments = []);

    /**
     * Apply inflections to the subject object.
     *
     * @param $object
     * @return mixed
     */
    public function apply($object);
}