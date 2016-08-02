<?php declare(strict_types = 1);


/**
 * Class RedirectResponseTest
 */
class RedirectResponseTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function implementsResponseContract()
    {
        $this->assertInstanceOf(\Abava\Http\Contract\Response::class, new \Abava\Http\RedirectResponse(''));
    }
}