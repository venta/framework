<?php

namespace spec\Venta\Http;

use PhpSpec\ObjectBehavior;
use Venta\Http\ResponseEmitter;

class ResponseEmitterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ResponseEmitter::class);
    }
}
