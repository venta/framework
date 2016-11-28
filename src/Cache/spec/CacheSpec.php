<?php

namespace spec\Venta\Cache;

use PhpSpec\ObjectBehavior;
use Psr\Cache\CacheItemPoolInterface;
use Venta\Cache\Cache;

class CacheSpec extends ObjectBehavior
{
    function let(CacheItemPoolInterface $cacheItemPool)
    {
        $this->beConstructedWith($cacheItemPool);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Cache::class);
    }
}
