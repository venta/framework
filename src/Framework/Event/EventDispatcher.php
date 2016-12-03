<?php declare(strict_types = 1);

namespace Venta\Framework\Event;

use Venta\Contracts\Container\Container;
use Venta\Contracts\Event\Event as EventContract;
use Venta\Event\EventDispatcher as BaseEventDispatcher;

/**
 * Class EventDispatcher
 *
 * @package Venta\Framework
 */
class EventDispatcher extends BaseEventDispatcher
{
    /**
     * @var Container
     */
    private $container;

    /**
     * CommandCollector constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    protected function callListener($listener, EventContract $event)
    {
        $this->container->call($listener, [$event]);
    }

    /**
     * @inheritDoc
     */
    protected function canBeCalled($listener): bool
    {
        return $this->container->isCallable($listener);
    }
}