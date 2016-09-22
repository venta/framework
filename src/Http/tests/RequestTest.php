<?php

use PHPUnit\Framework\TestCase;

/**
 * Class RequestTest
 */
class RequestTest extends TestCase
{
    /**
     * @test
     */
    public function implementsRequestContract()
    {
        $this->assertInstanceOf(\Venta\Contracts\Http\Request::class, new \Venta\Http\Request);
    }

}
