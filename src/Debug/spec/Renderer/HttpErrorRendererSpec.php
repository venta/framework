<?php

namespace spec\Venta\Debug\Renderer;

use Error;
use Exception;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Venta\Contracts\Debug\ErrorRenderer;
use Venta\Contracts\Http\Response;
use Venta\Contracts\Http\ResponseEmitter;
use Venta\Contracts\Http\ResponseFactory;

class HttpErrorRendererSpec extends ObjectBehavior
{
    function let(ResponseFactory $responseFactory, ResponseEmitter $responseEmitter)
    {
        $this->beConstructedWith($responseFactory, $responseEmitter);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(ErrorRenderer::class);
    }

    function it_renders_error(
        ResponseFactory $responseFactory, ResponseEmitter $responseEmitter, Response $response, Error $e
    ) {
        $responseFactory->createHtmlResponse(Argument::type('string'))->willReturn($response);
        $this->render($e);
        $responseEmitter->emit($response)->shouldHaveBeenCalled();
    }

    function it_renders_exception(
        ResponseFactory $responseFactory, ResponseEmitter $responseEmitter, Response $response, Exception $e
    ) {
        $responseFactory->createHtmlResponse(Argument::type('string'))->willReturn($response);
        $this->render($e);
        $responseEmitter->emit($response)->shouldHaveBeenCalled();
    }
}
