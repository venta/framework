<?php declare(strict_types = 1);

namespace Venta\Cache;

use Cache\Adapter\Common\CacheItem;
use Psr\Cache\CacheItemPoolInterface;
use Venta\Contracts\Cache\Cache as CacheContract;

/**
 * Class Cache
 *
 * @package Venta\Cache
 */
final class Cache implements CacheContract
{

    /**
     * @var CacheItemPoolInterface
     */
    private $pool;

    /**
     * Repository constructor.
     *
     * @param CacheItemPoolInterface $pool
     */
    public function __construct(CacheItemPoolInterface $pool)
    {
        $this->pool = $pool;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $key): bool
    {
        return $this->pool->deleteItem($key);
    }

    /**
     * @inheritDoc
     */
    public function get(string $key)
    {
        return $this->pool->getItem($key)->get();
    }

    /**
     * @inheritDoc
     */
    public function has(string $key): bool
    {
        return $this->pool->hasItem($key);
    }

    /**
     * @inheritDoc
     */
    public function put(string $key, $value, $expires): bool
    {
        $item = new CacheItem($key);
        $item->set($value);
        if (is_int($expires) || $expires instanceof \DateInterval) {
            $item->expiresAfter($expires);
        } elseif ($expires instanceof \DateTimeInterface) { // @codeCoverageIgnore
            $item->expiresAt($expires);
        }

        return $this->pool->save($item);
    }

    /**
     * @inheritDoc
     */
    public function set(string $key, $value): bool
    {
        return $this->pool->save((new CacheItem($key))->set($value));
    }

}
