<?php

use PHPUnit\Framework\TestCase;

/**
 * Class EmitterTest
 */
class EmitterTest extends TestCase
{
    /**
     * @test
     */
    public function implementsEmitterContract()
    {
        $this->assertInstanceOf(\Venta\Contracts\Http\ResponseEmitter::class, new Venta\Http\ResponseEmitter);
    }

}
