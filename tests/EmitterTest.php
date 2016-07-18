<?php declare(strict_types = 1);

class EmitterTest extends PHPUnit_Framework_TestCase
{

    public function testCreateEmitterInstance()
    {
        $emitter = new \Abava\Http\Emitter();
        $this->assertInstanceOf(\Abava\Http\Contract\Emitter::class, $emitter);
    }

}
