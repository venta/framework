<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use Venta\Contracts\Container\MutableContainer;
use Venta\Contracts\Event\EventDispatcher;
use Venta\Framework\Event\EventDispatcher as ContainerAwareEventDispatcher;
use Venta\ServiceProvider\AbstractServiceProvider;

/**
 * Class EventDispatcherServiceProvider
 *
 * @package Venta\Framework
 */
final class EventServiceProvider extends AbstractServiceProvider
{
    /**
     * @inheritDoc
     */
    public function bind(MutableContainer $container)
    {
        $container->bind(EventDispatcher::class, ContainerAwareEventDispatcher::class);
    }
}