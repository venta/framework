<?php

namespace spec\Venta\Framework\Event;

use Venta\Framework\Event\EventDispatcher;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EventDispatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(EventDispatcher::class);
    }
}
