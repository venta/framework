<?php

namespace spec\Venta\Debug\Renderer;

use PhpSpec\ObjectBehavior;
use Venta\Contracts\Debug\ErrorRenderer;

class ConsoleErrorRendererSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldImplement(ErrorRenderer::class);
    }
}
