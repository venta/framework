<?php

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Uri;

/**
 * Class RequestFactoryTest
 */
class RequestFactoryTest extends TestCase
{
    /**
     * @var \Abava\Http\Factory\RequestFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new \Abava\Http\Factory\RequestFactory;
    }

    /**
     * @test
     */
    public function implementsRequestFactoryContract()
    {
        $this->assertInstanceOf(\Abava\Http\Contract\RequestFactory::class, new \Abava\Http\Factory\RequestFactory);
    }

    /**
     * @test
     */
    public function canCreateServerRequestFromGlobals()
    {
        $request = $this->factory->createServerRequestFromGlobals();
        $this->assertInstanceOf(\Abava\Http\Contract\Request::class, $request);
    }

    /**
     * @test
     */
    public function canCreateServerRequestWithParams()
    {
        $request = $this->factory->createServerRequest('GET', '/foo.bar');
        $this->assertInstanceOf(\Abava\Http\Contract\Request::class, $request);
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/foo.bar', $request->getUri()->__toString());
    }

    /**
     * @test
     */
    public function canCreateRequestWithUriInstance()
    {
        $uri = new Uri('/foo.bar');
        $request = $this->factory->createServerRequest('GET', $uri);
        $this->assertInstanceOf(\Abava\Http\Contract\Request::class, $request);
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($uri, $request->getUri());
    }
}