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
        $this->assertInstanceOf(\Venta\Http\Contract\Response::class, new \Venta\Http\RedirectResponse(''));
    }
}