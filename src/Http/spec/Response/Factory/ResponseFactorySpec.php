<?php

namespace spec\Venta\Http\Factory;

use PhpSpec\ObjectBehavior;
use Venta\Contracts\Http\ResponseFactory as ResponseFactoryContract;
use Venta\Http\Factory\ResponseFactory;

class ResponseFactorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ResponseFactory::class);
        $this->shouldImplement(ResponseFactoryContract::class);
    }
}
