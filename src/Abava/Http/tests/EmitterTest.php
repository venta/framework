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
        $this->assertInstanceOf(\Abava\Http\Contract\Emitter::class, new \Abava\Http\Emitter);
    }

}
