<?php declare(strict_types = 1);

namespace Venta\Mail;

use Swift_Events_EventObject;
use Venta\Contracts\Event\EventManager as EventManagerContract;

/**
 * Class EventManagerAdapter
 *
 * @package Venta\Mail\Events
 */
class EventManagerAdapter extends \Swift_Events_SimpleEventDispatcher
{
    /**
     * EventDispatcher constructor.
     *
     * @param EventManagerContract $eventManager
     */
    public function __construct(EventManagerContract $eventManager)
    {
        parent::__construct();
        $this->em = $eventManager;
    }

    /** @inheritdoc */
    public function dispatchEvent(Swift_Events_EventObject $evt, $target)
    {
        parent::dispatchEvent($evt, $target);
        if ($evt->bubbleCancelled()) {
            return;
        }
        $eventName = $this->normalizeEventName($evt, $target);
        $this->em->trigger($eventName, ['swiftEventObject' => $evt]);
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