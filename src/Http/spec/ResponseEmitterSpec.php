<?php

namespace spec\Venta\Http;

use phpmock\prophecy\PHPProphet;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Prophecy\Prophecy\ProphecyInterface;
use Venta\Contracts\Http\ResponseEmitter;
use Zend\Diactoros\Response;

class ResponseEmitterSpec extends ObjectBehavior
{
    /**
     * @var ProphecyInterface
     */
    protected $prophecy;

    /**
     * @var PHPProphet
     */
    protected $prophet;

    function let()
    {
        $this->prophet = new PHPProphet();
        $this->prophecy = $this->prophet->prophesize('Zend\Diactoros\Response');
    }

    function it_doesnt_emit_headers_if_already_sent()
    {
        $prophecy = $this->prophet->prophesize('Zend\Diactoros\Response');
        $prophecy->header(Argument::cetera())->shouldNotBeCalled();
        $prophecy->reveal();

        $prophecy = $this->prophet->prophesize('Venta\Http');
        $prophecy->headers_sent()->willReturn(true);
        $prophecy->reveal();

        $this->emit(new Response('php://memory', 200, ['header' => 'value']));
        $this->prophet->checkPredictions();
    }

    function it_emits_body()
    {
        $prophecy = $this->prophet->prophesize('Venta\Http');
        $prophecy->headers_sent()->willReturn(true);
        $prophecy->reveal();

        $response = new Response('php://memory', 200, ['header' => 'value']);
        $body = "Hi there, I'm Venta!";
        $response->getBody()->write($body);
        ob_start();
        $this->emit($response);
        assert(ob_get_contents() === $body);
        ob_end_clean();
        $this->prophet->checkPredictions();
    }

    function it_emits_headers_and_status()
    {
        $prophecy = $this->prophet->prophesize('Zend\Diactoros\Response');
        $prophecy->header(Argument::that(function ($header) {
            return in_array($header, ['Header: value', 'HTTP/1.1 200 OK']);
        }), Argument::cetera())->shouldBeCalled();
        $prophecy->reveal();

        $prophecy = $this->prophet->prophesize('Venta\Http');
        $prophecy->headers_sent()->willReturn(false);
        $prophecy->reveal();

        $this->emit(new Response('php://memory', 200, ['header' => 'value']));
        $this->prophet->checkPredictions();
    }

    function it_is_initializable()
    {
        $this->shouldImplement(ResponseEmitter::class);
    }
}
