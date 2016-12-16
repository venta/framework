<?php

namespace spec\Venta\Http;

use PhpSpec\ObjectBehavior;
use Venta\Contracts\Http\ResponseEmitter;

class ResponseEmitterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldImplement(ResponseEmitter::class);
    }
}
