<?php declare(strict_types = 1);
/**
 * Class EventDispatcher
 *
 * @package Abava\Mail\Events
 */


namespace Abava\Mail;

use Abava\Event\Contract\EventManager as EventManagerContract;

/**
 * Class EventDispatcher
 *
 * @package Abava\Mail\Events
 */
class EventDispatcherAdapter extends \Swift_Events_SimpleEventDispatcher
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
    public function dispatchEvent(\Swift_Events_EventObject $evt, $target)
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
    protected function normalizeEventName(\Swift_Events_EventObject $evt, $target)
    {
        return strtolower(
            str_replace('_', '.', get_class($evt)) .
            sprintf(".%s", $target)
        );
    }
}