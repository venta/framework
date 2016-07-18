<?php declare(strict_types = 1);

/**
 * Class RequestTest
 */
class RequestTest extends PHPUnit_Framework_TestCase
{

    public function testCreateNewRequestInstance()
    {
        $request = new \Abava\Http\Request();
        $this->assertInstanceOf(\Abava\Http\Contract\Request::class, $request);
    }

}
