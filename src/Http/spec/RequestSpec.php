<?php

namespace spec\Venta\Http;

use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Http\Request as RequestContract;
use Venta\Http\Request;

class RequestSpec extends ObjectBehavior
{
    function let(ServerRequestInterface $psrRequest)
    {
        $this->beConstructedWith($psrRequest);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Request::class);
        $this->shouldImplement(RequestContract::class);
        $this->shouldImplement(ServerRequestInterface::class);
    }
}
