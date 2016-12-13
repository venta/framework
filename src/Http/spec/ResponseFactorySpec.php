<?php

namespace spec\Venta\Http;

use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Venta\Contracts\Http\ResponseFactory as ResponseFactoryContract;

class ResponseFactorySpec extends ObjectBehavior
{
    function it_creates_empty_response()
    {
        $response = $this->createEmptyResponse();

        $response->shouldBeAnInstanceOf(ResponseInterface::class);
        $response->getStatusCode()->shouldBe(204);
        $response->getBody()->shouldBeAnInstanceOf(StreamInterface::class);
        $response->getBody()->getContents()->shouldBe('');
    }

    function it_creates_empty_response_with_headers()
    {
        $response = $this->createEmptyResponse(204, ['header' => 'value']);

        $response->shouldBeAnInstanceOf(ResponseInterface::class);
        $response->getHeader('header')[0]->shouldBe('value');
        $response->getBody()->shouldBeAnInstanceOf(StreamInterface::class);
    }

    function it_creates_html_response()
    {
        $response = $this->createHtmlResponse('html', 201, ['header' => 'value']);

        $response->shouldBeAnInstanceOf(ResponseInterface::class);
        $response->getStatusCode()->shouldBe(201);
        $response->getHeader('header')[0]->shouldBe('value');
        $response->getHeader('content-type')[0]->shouldBe('text/html; charset=utf-8');
        $response->getBody()->shouldBeAnInstanceOf(StreamInterface::class);
        $response->getBody()->getContents()->shouldBe('html');
    }

    function it_creates_json_response()
    {
        $data = ['foo' => 'bar'];
        $response = $this->createJsonResponse($data, 201, ['header' => 'value']);

        $response->shouldBeAnInstanceOf(ResponseInterface::class);
        $response->getStatusCode()->shouldBe(201);
        $response->getHeader('header')[0]->shouldBe('value');
        $response->getHeader('content-type')[0]->shouldBe('application/json');
        $response->getBody()->shouldBeAnInstanceOf(StreamInterface::class);
        $response->getBody()->getContents()->shouldBe(json_encode($data));
    }

    function it_creates_redirect_response()
    {
        $response = $this->createRedirectResponse('uri');

        $response->shouldBeAnInstanceOf(ResponseInterface::class);
        $response->getStatusCode()->shouldBe(302);
        $response->getHeader('location')[0]->shouldBe('uri');
        $response->getBody()->shouldBeAnInstanceOf(StreamInterface::class);
        $response->getBody()->getContents()->shouldBe('');
    }

    function it_creates_redirect_response_with_custom_status_and_headers()
    {
        $response = $this->createRedirectResponse('uri', 301, ['header' => 'value']);

        $response->getStatusCode()->shouldBe(301);
        $response->getHeader('header')[0]->shouldBe('value');
    }

    function it_creates_response()
    {
        $response = $this->createResponse('php://memory', 201, ['header' => 'value']);

        $response->shouldBeAnInstanceOf(ResponseInterface::class);
        $response->getStatusCode()->shouldBe(201);
        $response->getHeader('header')[0]->shouldBe('value');
        $response->getBody()->shouldBeAnInstanceOf(StreamInterface::class);
        $response->getBody()->getContents()->shouldBe('');
    }

    function it_creates_text_response()
    {
        $response = $this->createTextResponse('text', 201, ['header' => 'value']);

        $response->shouldBeAnInstanceOf(ResponseInterface::class);
        $response->getStatusCode()->shouldBe(201);
        $response->getHeader('header')[0]->shouldBe('value');
        $response->getHeader('content-type')[0]->shouldBe('text/plain; charset=utf-8');
        $response->getBody()->shouldBeAnInstanceOf(StreamInterface::class);
        $response->getBody()->getContents()->shouldBe('text');
    }

    function it_is_initializable()
    {
        $this->shouldImplement(ResponseFactoryContract::class);
    }
}
