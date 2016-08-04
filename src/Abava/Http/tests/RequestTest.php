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
        $this->assertInstanceOf(\Abava\Http\Contract\Request::class, new \Abava\Http\Request);
    }

}
