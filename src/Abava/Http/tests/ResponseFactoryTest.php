<?php

use PHPUnit\Framework\TestCase;

class ResponseFactoryTest extends TestCase
{
    /**
     * @var \Abava\Http\Factory\ResponseFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new \Abava\Http\Factory\ResponseFactory();
    }

    /**
     * @test
     */
    public function implementsResponseFactoryContract()
    {
        $this->assertInstanceOf(\Abava\Http\Contract\ResponseFactory::class, $this->factory);
    }

    /**
     * @test
     */
    public function canCreateResponse()
    {
        $response = $this->factory->createResponse();
        $this->assertInstanceOf(\Abava\Http\Response::class, $response);
        $this->assertSame($response->getStatusCode(), 200);
    }

    /**
     * @test
     */
    public function canCreateResponseWithStatus()
    {
        $response = $this->factory->createResponse(500);
        $this->assertSame($response->getStatusCode(), 500);
    }

    /**
     * @test
     */
    public function implementsRedirectResponseFactoryContract()
    {
        $this->assertInstanceOf(\Abava\Http\Contract\RedirectResponseFactory::class, $this->factory);
    }

    /**
     * @test
     */
    public function canCreateRedirectResponse()
    {
        $response = $this->factory->createRedirectResponse('/foo.bar');
        $this->assertInstanceOf(\Abava\Http\RedirectResponse::class, $response);
        $this->assertSame('/foo.bar', $response->getHeaderLine('Location'));
        $this->assertSame($response->getStatusCode(), 302);
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
    public function implementsJsonResponseFactoryContract()
    {
        $this->assertInstanceOf(\Abava\Http\Contract\JsonResponseFactory::class, $this->factory);
    }

    /**
     * @test
     */
    public function canCreateJsonResponse()
    {
        $data = ['foo' => 'bar'];
        $response = $this->factory->createJsonResponse($data);
        $this->assertInstanceOf(\Abava\Http\JsonResponse::class, $response);
        $this->assertJsonStringEqualsJsonString(json_encode($data), $response->getBody()->__toString());
    }
}
