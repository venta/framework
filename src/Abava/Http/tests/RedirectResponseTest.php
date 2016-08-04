<?php

use PHPUnit\Framework\TestCase;

/**
 * Class RedirectResponseTest
 */
class RedirectResponseTest extends TestCase
{
    /**
     * @test
     */
    public function implementsResponseContract()
    {
        $this->assertInstanceOf(\Abava\Http\Contract\Response::class, new \Abava\Http\RedirectResponse(''));
    }
}