<?php

use PHPUnit\Framework\TestCase;

class ResponseEmitterTest extends TestCase
{
    /**
     * @test
     */
    public function implementsEmitterContract()
    {
        $this->assertInstanceOf(\Venta\Contracts\Http\ResponseEmitter::class, new Venta\Http\ResponseEmitter);
    }

}
