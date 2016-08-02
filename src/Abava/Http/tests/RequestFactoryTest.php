<?php declare(strict_types = 1);


/**
 * Class RequestFactoryTest
 */
class RequestFactoryTest extends PHPUnit_Framework_TestCase
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
}