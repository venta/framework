<?php

use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @todo: test each scenario individually
     * @test
     */
    public function canGetSetHasDelete()
    {
        $pool = Mockery::mock(\Psr\Cache\CacheItemPoolInterface::class);
        $cache = new \Venta\Cache\Repository($pool);
        $pool->shouldReceive('hasItem')->with('key')->andReturn(false, true, false)->times(3);

        $this->assertFalse($cache->has('key'));

        $pool->shouldReceive('save')->with(Mockery::type(\Psr\Cache\CacheItemInterface::class))->andReturn(true);
        $this->assertTrue($cache->set('key', 'value'));

        $this->assertTrue($cache->has('key'));

        $pool->shouldReceive('getItem')->with('key')->andReturn(
            Mockery::mock(\Psr\Cache\CacheItemInterface::class)
                   ->shouldReceive('get')
                   ->withNoArgs()
                   ->andReturn('value')
                   ->getMock()
        );
        $this->assertSame('value', $cache->get('key'));

        $pool->shouldReceive('deleteItem')->with('key')->andReturn(true);
        $this->assertTrue($cache->delete('key'));

        $this->assertFalse($cache->has('key'));
    }

    /**
     * @test
     */
    public function canPutWithDateTimeExpire()
    {
        $pool = Mockery::mock(\Psr\Cache\CacheItemPoolInterface::class);
        $cache = new \Venta\Cache\Repository($pool);

        $pool->shouldReceive('save')->with(Mockery::on(function (\Cache\Adapter\Common\CacheItem $cacheItem) {
            $this->assertSame('key', $cacheItem->getKey());
            $this->assertEquals('2030-01-01 00:00:00', $cacheItem->getExpirationDate()->format('Y-m-d H:i:s'));

            return true;
        }))->andReturn(true);

        $this->assertTrue($cache->put('key', 'value', new DateTime('2030-01-01 00:00:00')));
    }

    /**
     * @test
     */
    public function canPutWithIntExpire()
    {
        $pool = Mockery::mock(\Psr\Cache\CacheItemPoolInterface::class);
        $cache = new \Venta\Cache\Repository($pool);

        $pool->shouldReceive('save')->with(Mockery::on(function (\Cache\Adapter\Common\CacheItem $cacheItem) {
            $this->assertSame('key', $cacheItem->getKey());
            $this->assertEquals(time() + 10, $cacheItem->getExpirationDate()->getTimestamp());

            return true;
        }))->andReturn(true);

        $this->assertTrue($cache->put('key', 'value', 10));
    }

    /**
     * @test
     */
    public function canPutWithIntervalExpire()
    {
        $pool = Mockery::mock(\Psr\Cache\CacheItemPoolInterface::class);
        $cache = new \Venta\Cache\Repository($pool);

        $interval = new DateInterval('P1M');

        $pool->shouldReceive('save')->with(Mockery::on(function (\Cache\Adapter\Common\CacheItem $cacheItem) use (
            $interval
        ) {
            $this->assertSame('key', $cacheItem->getKey());
            $this->assertEquals(
                (new \DateTime())->add($interval)->getTimestamp(),
                $cacheItem->getExpirationDate()->getTimestamp()
            );

            return true;
        }))->andReturn(true);

        $this->assertTrue($cache->put('key', 'value', $interval));
    }

    /**
     * @test
     */
    public function canPutWithoutExpire()
    {
        $pool = Mockery::mock(\Psr\Cache\CacheItemPoolInterface::class);
        $cache = new \Venta\Cache\Repository($pool);

        $pool->shouldReceive('save')->with(Mockery::on(function (\Cache\Adapter\Common\CacheItem $cacheItem) {
            $this->assertSame('key', $cacheItem->getKey());
            $this->assertNull($cacheItem->getExpirationDate());

            return true;
        }))->andReturn(true);

        $this->assertTrue($cache->put('key', 'value', 'invalid time'));
    }

}
