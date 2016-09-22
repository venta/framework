<?php

use PHPUnit\Framework\TestCase;
use Zend\Diactoros\Uri;

/**
 * Class RequestFactoryTest
 */
class RequestFactoryTest extends TestCase
{
    /**
     * @var \Venta\Http\Factory\RequestFactory
     */
    protected $factory;

    public function setUp()
    {
        $this->factory = new \Venta\Http\Factory\RequestFactory;
    }

    /**
     * @test
     */
    public function canCreateRequestWithUriInstance()
    {
        $uri = new Uri('/foo.bar');
        $request = $this->factory->createServerRequest('GET', $uri);
        $this->assertInstanceOf(\Venta\Contracts\Http\Request::class, $request);
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame($uri, $request->getUri());
    }

    /**
     * @test
     */
    public function canCreateServerRequestFromGlobals()
    {
        $request = $this->factory->createServerRequestFromGlobals();
        $this->assertInstanceOf(\Venta\Contracts\Http\Request::class, $request);
    }

    /**
     * @test
     */
    public function canCreateServerRequestWithParams()
    {
        $request = $this->factory->createServerRequest('GET', '/foo.bar');
        $this->assertInstanceOf(\Venta\Contracts\Http\Request::class, $request);
        $this->assertSame('GET', $request->getMethod());
        $this->assertSame('/foo.bar', $request->getUri()->__toString());
    }

    /**
     * @test
     */
    public function implementsRequestFactoryContract()
    {
        $this->assertInstanceOf(\Venta\Contracts\Http\RequestFactory::class, new \Venta\Http\Factory\RequestFactory);
    }
}