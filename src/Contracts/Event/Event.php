<?php declare(strict_types = 1);

namespace Venta\Contracts\Event;

/**
 * Interface Event
 *
 * @package Venta\Contracts\Event
 */
interface Event
{
    /**
     * Has this event indicated event propagation should stop?
     *
     * @return bool
     */
    public function isPropagationStopped(): bool;

    /**
     * Indicate whether or not to stop propagating this event
     *
     * @return void
     */
    public function stopPropagation();
}