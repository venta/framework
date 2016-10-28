<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use Venta\Contracts\Event\EventDispatcher;
use Venta\Framework\Event\EventDispatcher as ContainerAwareEventDispatcher;
use Venta\ServiceProvider\AbstractServiceProvider;

/**
 * Class EventDispatcherServiceProvider
 *
 * @package Venta\Framework
 */
class EventServiceProvider extends AbstractServiceProvider
{
    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->container->set(EventDispatcher::class, ContainerAwareEventDispatcher::class, true);
    }
}