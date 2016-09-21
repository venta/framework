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
        $this->assertInstanceOf(\Venta\Http\Contract\Emitter::class, new \Venta\Http\Emitter);
    }

}
