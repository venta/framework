<?php

use PHPUnit\Framework\TestCase;

/**
 * Class RedirectResponseFactoryTest
 */
class RedirectResponseFactoryTest extends TestCase
{
    /**
     * @var \Abava\Http\Factory\RedirectResponseFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new \Abava\Http\Factory\RedirectResponseFactory();
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
        $response = $this->factory->createResponse('/foo.bar');
        $this->assertInstanceOf(\Abava\Http\RedirectResponse::class, $response);
        $this->assertSame('/foo.bar', $response->getHeaderLine('Location'));
        $this->assertSame($response->getStatusCode(), 302);
    }

    /**
     * @test
     */
    public function canCreateRedirectResponseWithStatus()
    {
        $response = $this->factory->createResponse('/foo.bar', 301);
        $this->assertSame($response->getStatusCode(), 301);
    }

    /**
     * @test
     */
    public function canCreateRedirectResponseWithHeaders()
    {
        $response = $this->factory->createResponse('/foo.bar', 302, ['key' => 'value']);
        $this->assertArrayHasKey('key', $response->getHeaders());
        $this->assertSame(true, $response->hasHeader('key'));
        $this->assertSame('value', $response->getHeader('key')[0]);
    }


}