<?php

use PHPUnit\Framework\TestCase;

class JsonResponseTest extends TestCase
{
    /**
     * @test
     */
    public function implementsResponseContract()
    {
        $this->assertInstanceOf(\Venta\Contracts\Http\Response::class, new \Venta\Http\JsonResponse(null));
    }
}