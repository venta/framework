<?php

use PHPUnit\Framework\TestCase;

class JsonResponseTest extends TestCase
{
    /**
     * @test
     */
    public function implementsResponseContract()
    {
        $this->assertInstanceOf(\Venta\Http\Contract\Response::class, new \Venta\Http\JsonResponse(null));
    }
}