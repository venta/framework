<?php

class ResponseFactoryTest extends PHPUnit_Framework_TestCase
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
}
