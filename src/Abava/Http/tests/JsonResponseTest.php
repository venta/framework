<?php

use PHPUnit\Framework\TestCase;

class JsonResponseTest extends TestCase
{
    /**
     * @test
     */
    public function implementsResponseContract()
    {
        $this->assertInstanceOf(\Abava\Http\Contract\Response::class, new \Abava\Http\JsonResponse(null));
    }
}