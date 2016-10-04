<?php declare(strict_types = 1);

namespace Venta\Contracts\Event;

/**
 * Interface Dispatcher
 *
 * @package Venta\Contracts\Event
 */
interface EventDispatcher
{
    /**
     * Add event listener.
     *
     * @param string   $eventName
     * @param callable $listener
     * @param int      $priority
     */
    public function attach(string $eventName, callable $listener, int $priority = 0);

    /**
     * Clear all listeners for a given event.
     *
     * @param  string $eventName
     */
    public function clearListeners(string $eventName);

    /**
     * Returns new event instance.
     *
     * @param  string $eventName
     * @param  array  $data
     * @return Event
     */
    public function createEvent(string $eventName, array $data = []): Event;

    /**
     * Trigger an event.
     *
     * @param  string $eventName
     * @param  array  $data
     */
    public function trigger(string $eventName, array $data = []);
}