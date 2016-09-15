<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Abava\Event\Contract\EventManager;

/**
 * Interface EventProvider
 *
 * @package Venta\Contract\ExtensionProvider
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