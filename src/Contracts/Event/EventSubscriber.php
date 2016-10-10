<?php declare(strict_types = 1);

namespace Venta\Contracts\Event;

/**
 * Interface EventSubscriber
 *
 * @package Venta\Contracts\Event
 */
interface EventSubscriber
{
    /**
     * Returns array of subscribed events.
     *
     * @return array
     */
    public function getSubscriptions(): array;
}