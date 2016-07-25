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

    public function testCreateResponse()
    {
        $stream = new \Zend\Diactoros\Stream('php://memory');
        $response = $this->factory->createResponse(500, ['X-Header'=>'value'], $stream);
        $this->assertInstanceOf(\Abava\Http\Response::class, $response);
        $this->assertSame($stream, $response->getBody());
        $this->assertSame(500, $response->getStatusCode());
        $this->assertSame([0 => 'value'], $response->getHeader('X-Header'));
    }

    public function testNew()
    {
        $response = $this->factory->new();
        $this->assertInstanceOf(\Abava\Http\Response::class, $response);
    }

    public function testRedirect()
    {
        $response = $this->factory->redirect('url', 301);
        $this->assertInstanceOf(\Abava\Http\Response::class, $response);
        $this->assertSame($response->getStatusCode(), 301);
        $this->assertSame('url', $response->getHeaderLine('Location'));
    }

}
