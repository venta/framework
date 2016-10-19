<?php declare(strict_types = 1);

namespace Venta\Mail;

use Swift_Events_EventObject;
use Venta\Contracts\Event\EventDispatcher;

/**
 * Class EventDispatcher
 *
 * @package Venta\Mail\Events
 */
class EventDispatcherAdapter extends \Swift_Events_SimpleEventDispatcher
{
    /**
     * @var EventDispatcher
     */
    private $eventDispatcher;

    /**
     * EventDispatcherAdapter constructor.
     *
     * @param EventDispatcher $eventDispatcher
     */
    public function __construct(EventDispatcher $eventDispatcher)
    {
        parent::__construct();
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @inheritdoc
     */
    public function dispatchEvent(Swift_Events_EventObject $evt, $target)
    {
        parent::dispatchEvent($evt, $target);
        if ($evt->bubbleCancelled()) {
            return;
        }
        $eventName = $this->normalizeEventName($evt, $target);
        $this->eventDispatcher->trigger($eventName, ['swiftEventObject' => $evt]);
    }

    /**
     * @param \Swift_Events_EventObject $evt
     * @param $target
     * @return string
     */
    protected function normalizeEventName(Swift_Events_EventObject $evt, $target)
    {
        return strtolower(
            str_replace('_', '.', get_class($evt)) .
            sprintf(".%s", $target)
        );
    }
}