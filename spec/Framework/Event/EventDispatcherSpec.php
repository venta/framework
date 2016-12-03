<?php

namespace spec\Venta\Framework\Event;

use PhpSpec\ObjectBehavior;
use Venta\Contracts\Container\Container;
use Venta\Framework\Event\EventDispatcher;

class EventDispatcherSpec extends ObjectBehavior
{
    function let(Container $container)
    {
        $this->beConstructedWith($container);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(EventDispatcher::class);
    }
}
