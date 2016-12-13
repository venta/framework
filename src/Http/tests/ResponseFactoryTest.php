<?php

use PHPUnit\Framework\TestCase;
use Venta\Contracts\Http\Response;

class ResponseFactoryTest extends TestCase
{
    /**
     * @var \Venta\Http\Factory\ResponseFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new \Venta\Http\Factory\ResponseFactory();
    }

    /**
     * @test
     */
    public function canCreateJsonResponse()
    {
        $data = ['foo' => 'bar'];
        $response = $this->factory->createJsonResponse($data);
        $this->assertInstanceOf(Response::class, $response);
        $this->assertJsonStringEqualsJsonString(json_encode($data), $response->content());
    }

    /**
     * @test
     */
    public function canCreateRedirectResponse()
    {
        $response = $this->factory->createRedirectResponse('/foo.bar');
        $this->assertInstanceOf(Response::class, $response);
        $this->assertSame('/foo.bar', $response->getHeaderLine('Location'));
        $this->assertSame($response->getStatusCode(), 302);
    }

    /**
     * @test
     */
    public function canCreateRedirectResponseWithHeaders()
    {
        $response = $this->factory->createRedirectResponse('/foo.bar', 302, ['key' => 'value']);
        $this->assertArrayHasKey('key', $response->getHeaders());
        $this->assertSame(true, $response->hasHeader('key'));
        $this->assertSame('value', $response->getHeader('key')[0]);
    }

    /**
     * @test
     */
    public function canCreateRedirectResponseWithStatus()
    {
        $response = $this->factory->createRedirectResponse('/foo.bar', 301);
        $this->assertSame($response->getStatusCode(), 301);
    }

    /**
     * @test
     */
    public function canCreateResponse()
    {
        $response = $this->factory->createResponse();
        $this->assertInstanceOf(\Venta\Http\Response::class, $response);
        $this->assertSame($response->getStatusCode(), 200);
    }

    /**
     * @test
     */
    public function canCreateResponseWithStatus()
    {
        $response = $this->factory->createResponse('php://memory', 500);
        $this->assertSame($response->getStatusCode(), 500);
    }

    /**
     * @test
     */
    public function implementsResponseFactoryContract()
    {
        $this->assertInstanceOf(\Venta\Contracts\Http\ResponseFactory::class, $this->factory);
    }
}
