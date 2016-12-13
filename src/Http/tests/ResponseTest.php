<?php

use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Venta\Http\Response;

/**
 * Class ResponseTest
 */
class ResponseTest extends TestCase
{
    /**
     * @var StreamInterface|MockInterface
     */
    private $body;

    /**
     * @var ResponseInterface|MockInterface
     */
    private $psr;

    /**
     * @var Response
     */
    private $response;

    public function setUp()
    {
        $this->body = Mockery::spy(StreamInterface::class);
        $this->psr = Mockery::spy(ResponseInterface::class, ['getBody' => $this->body]);
        $this->response = new Response($this->psr);
    }

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function canAppendStringToBody()
    {
        $result = $this->response->append('abc');

        $this->assertInstanceOf(\Venta\Contracts\Http\Response::class, $result);
        $this->assertSame($this->response, $result);
        $this->body->shouldHaveReceived('write')->with('abc');
    }

    /**
     * @return @test
     */
    public function canBeInitialized()
    {
        $this->body->shouldReceive('__toString')->andReturn('');

        $this->assertEmpty($this->response->content());

        $this->body->shouldHaveReceived('__toString')->withNoArgs();
    }

    /**
     * @test
     */
    public function implementsResponseContract()
    {
        $this->assertInstanceOf(\Venta\Contracts\Http\Response::class, $this->response);
    }
}