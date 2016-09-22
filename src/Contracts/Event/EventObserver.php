<?php declare(strict_types = 1);

namespace Venta\Contracts\Event;

/**
 * Interface EventObserver
 *
 * @package Venta\Contracts\Event
 */
interface EventObserver
{
    /**
     * @return string|callable
     */
    public function getCallback();

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return integer
     */
    public function getPriority();
}