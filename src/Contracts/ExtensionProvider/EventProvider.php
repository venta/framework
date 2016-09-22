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
     * Function, called in order to collect defined events
     *
     * @param EventManager $manager
     * @return mixed
     */
    public function provideEvents(EventManager $manager);
}