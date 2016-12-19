<?php

namespace spec\Venta\Debug;

use PhpSpec\ObjectBehavior;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Debug\ErrorReporterAggregate;

class ErrorReporterAggregateSpec extends ObjectBehavior
{
    function let(Container $container)
    {
        $this->beConstructedWith($container);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(ErrorReporterAggregate::class);
    }
}
