<?php declare(strict_types = 1);

namespace Venta\Contracts\Event;

/**
 * Interface EventFactory
 *
 * @package Venta\Contracts\Event
 */
interface EventFactory
{
    /**
     * Returns new event instance.
     *
     * @param  string $eventName
     * @param  array  $data
     * @return Event
     */
    public function create(string $eventName, array $data = []): Event;
}