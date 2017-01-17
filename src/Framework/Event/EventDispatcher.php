<?php declare(strict_types = 1);

namespace Venta\Framework\Event;

use Venta\Contracts\Container\Invoker;
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
     * @var Invoker
     */
    private $invoker;

    /**
     * EventDispatcher constructor.
     *
     * @param Invoker $container
     */
    public function __construct(Invoker $container)
    {
        $this->invoker = $container;
    }

    /**
     * @inheritDoc
     */
    protected function callListener($listener, EventContract $event)
    {
        $this->invoker->call($listener, [$event]);
    }

    /**
     * @inheritDoc
     */
    protected function canBeCalled($listener): bool
    {
        return $this->invoker->isCallable($listener);
    }
}