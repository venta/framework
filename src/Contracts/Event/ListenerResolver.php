<?php declare(strict_types = 1);

namespace Venta\Contracts\Event;

/**
 * Interface ListenerResolver
 *
 * @package Venta\Contracts\Event
 */
interface ListenerResolver
{
    /**
     * Listener resolver function setter.
     *
     * @param  callable $resolver
     */
    public function setListenerResolver(callable $resolver);

    /**
     * Listener resolver function getter.
     *
     * @return callable|mixed
     */
    public function getListenerResolver();
}