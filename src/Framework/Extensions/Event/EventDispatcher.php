<?php declare(strict_types = 1);

namespace Venta\Framework\Extensions\Event;

use Venta\Container\ContainerAwareTrait;
use Venta\Contracts\Container\ContainerAware;
use Venta\Contracts\Event\Event as EventContract;
use Venta\Event\EventDispatcher as BaseEventDispatcher;

/**
 * Class EventDispatcher
 *
 * @package Venta\Framework
 */
class EventDispatcher extends BaseEventDispatcher implements ContainerAware
{
    use ContainerAwareTrait;

    /**
     * @inheritDoc
     */
    protected function callListener($listener, EventContract $event)
    {
        $this->container->callWithArguments($listener, [$event]);
    }

    /**
     * @inheritDoc
     */
    protected function canBeCalled($listener): bool
    {
        return $this->container->isCallable($listener);
    }
}