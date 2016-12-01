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
     * @param  string $eventName
     * @param  callable $listener
     * @param  int    $priority
     * @return void
     */
    public function addListener(string $eventName, $listener, int $priority = 0);

    /**
     * Trigger an event.
     *
     * @param  Event $event
     * @return void
     */
    public function dispatch(Event $event);
}