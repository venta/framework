<?php declare(strict_types = 1);

namespace Venta\Contracts\Event;

/**
 * Interface Dispatcher
 *
 * @package Venta\Contracts\Event
 */
interface Dispatcher
{
    /**
     * Clear all listeners for a given event.
     *
     * @param  string $eventName
     */
    public function clearListeners(string $eventName);

    /**
     * Add event listener.
     *
     * @param string   $eventName
     * @param callable $listener
     * @param int      $priority
     */
    public function attach(string $eventName, callable $listener, int $priority = 0);

    /**
     * Trigger an event.
     *
     * @param  string $eventName
     * @param  array  $data
     */
    public function trigger(string $eventName, array $data = []);
}