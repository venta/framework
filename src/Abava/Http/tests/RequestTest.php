<?php declare(strict_types = 1);

/**
 * Class RequestTest
 */
class RequestTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function implementsRequestContract()
    {
        $this->assertInstanceOf(\Abava\Http\Contract\Request::class, new \Abava\Http\Request);
    }

}
