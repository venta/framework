<?php

namespace spec\Venta\Debug;

use PhpSpec\ObjectBehavior;
use Venta\Contracts\Debug\ErrorHandler;
use Venta\Contracts\Debug\ErrorRenderer;
use Venta\Contracts\Debug\ErrorReporterAggregate;

class ErrorHandlerSpec extends ObjectBehavior
{
    function let(ErrorRenderer $renderer, ErrorReporterAggregate $reporters)
    {
        $this->beConstructedWith($renderer, $reporters);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(ErrorHandler::class);
    }
}
