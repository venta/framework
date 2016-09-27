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
     * Returns event data by key.
     * If no key is passed, returns all available data
     *
     * @param null|mixed $key
     * @param null       $default
     * @return mixed
     */
    public function getData($key = null, $default = null);

    /**
     * Get event name.
     *
     * @return string
     */
    public function getName(): string;

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