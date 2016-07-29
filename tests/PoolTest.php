<?php declare(strict_types = 1);

/**
 * Class PoolTest
 */
class PoolTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function testSomething()
    {
        $pool = new Venta\Cache\Pool;

        var_dump($pool->save(new \Venta\Cache\Item('test', 'vak')));
    }
}