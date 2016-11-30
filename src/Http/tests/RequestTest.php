<?php

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RequestTest
 */
class RequestTest extends TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function implementsRequestContract()
    {
        $this->assertInstanceOf(
            \Venta\Contracts\Http\Request::class,
            new \Venta\Http\Request(Mockery::mock(ServerRequestInterface::class))
        );
    }

}
