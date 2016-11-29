<?php

namespace spec\Venta\Framework\Http;

use PhpSpec\ObjectBehavior;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Kernel\Kernel;
use Venta\Framework\Http\HttpApplication;

class HttpApplicationSpec extends ObjectBehavior
{
    function let(Kernel $kernel, Container $container)
    {
        $kernel->boot()->willReturn($container);
        $this->beConstructedWith($kernel);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HttpApplication::class);
    }
}
