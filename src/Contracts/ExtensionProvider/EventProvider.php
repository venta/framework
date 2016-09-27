<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Contracts\Event\EventManager;

/**
 * Interface EventProvider
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface EventProvider
{
    /**
     * Function, called in order to collect events.
     *
     * Expects array as return value, where array key is event name,
     * and array value(s) are listener definitions.
     *
     * @return array
     */
    public function provideEvents(): array;
}