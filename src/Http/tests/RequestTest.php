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
        $this->assertInstanceOf(\Venta\Http\Contract\Request::class, new \Venta\Http\Request);
    }

}
