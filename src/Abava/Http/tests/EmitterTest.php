<?php declare(strict_types = 1);

/**
 * Class EmitterTest
 */
class EmitterTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function implementsEmitterContract()
    {
        $this->assertInstanceOf(\Abava\Http\Contract\Emitter::class, new \Abava\Http\Emitter);
    }

}
