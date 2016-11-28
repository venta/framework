<?php

namespace spec\Venta\Http\Factory;

use PhpSpec\ObjectBehavior;
use Venta\Contracts\Http\RequestFactory as RequestFactoryContract;
use Venta\Http\Factory\RequestFactory;

class RequestFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(RequestFactory::class);
        $this->shouldImplement(RequestFactoryContract::class);
    }
}
