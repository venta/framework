<?php declare(strict_types = 1);

namespace Venta\Contracts\Event;

/**
 * Interface EventDispatcher
 *
 * @package Venta\Contracts\Event
 */
interface EventDispatcher
{
    /**
     * Attach an event listener.
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
     * Subscribes subscriber to dispatcher.
     *
     * @param  EventSubscriber $subscriber
     */
    public function subscribe(EventSubscriber $subscriber);

    /**
     * Trigger an event.
     *
     * @param  Event $event
     */
    public function trigger(Event $event);
}