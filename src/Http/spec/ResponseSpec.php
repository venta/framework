<?php

namespace spec\Venta\Http;

use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Venta\Contracts\Http\Response as ResponseContract;
use Venta\Http\Response;

class ResponseSpec extends ObjectBehavior
{
    function let(ResponseInterface $psrResponse)
    {
        $this->beConstructedWith($psrResponse);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Response::class);
        $this->shouldImplement(ResponseContract::class);
        $this->shouldImplement(ResponseInterface::class);
    }
}
