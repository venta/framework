<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use Cache\Adapter\PHPArray\ArrayCachePool;
use InvalidArgumentException;
use League\Flysystem\Filesystem;
use Psr\Cache\CacheItemPoolInterface;
use RuntimeException;
use Venta\Cache\Cache;
use Venta\Contracts\Cache\Cache as CacheContract;
use Venta\Contracts\Config\Config;
use Venta\ServiceProvider\AbstractServiceProvider;

/**
 * Class CacheServiceProvider
 *
 * @package Venta\Framework\ServiceProvider
 */
class CacheServiceProvider extends AbstractServiceProvider
{
    /**
     * @param $name
     * @param $arguments
     * @return void
     * @throws InvalidArgumentException
     */
    public function __call($name, $arguments)
    {
        throw new InvalidArgumentException(
            sprintf('Unknown cache driver "%s" specified.', substr($name, 0, strlen($name) - 16))
        );
    }

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->container()->bind(CacheContract::class, Cache::class);
        /** @var Config $config */
        $config = $this->container()->get(Config::class);

        $cacheDriver = $config->get('cache.driver');
        if ($cacheDriver === null) {
            throw new RuntimeException('Undefined cache driver.');
        }
        switch ($cacheDriver) {
            case 'array':
            case 'memory':
                $this->container()->bind(CacheItemPoolInterface::class, ArrayCachePool::class);
                break;
            case 'void':
            case 'null':
                $this->container()->bind(CacheItemPoolInterface::class, 'Cache\Adapter\Void\VoidCachePool');
                break;
            case 'file':
            case 'files':
            case 'filesystem':
                $this->container()
                    ->factory(CacheItemPoolInterface::class, $this->filesystemCachePoolFactory(), true);
                break;
            case 'redis':
                $this->container()->bind(CacheItemPoolInterface::class, 'Cache\Adapter\Redis\RedisCachePool');
                break;
            case 'predis':
                $this->container()->bind(CacheItemPoolInterface::class, 'Cache\Adapter\Predis\PredisCachePool');
                break;
            case 'memcached':
                $this->container()->bind(CacheItemPoolInterface::class, 'Cache\Adapter\Memcached\MemcachedCachePool');
                break;
            default:
                $this->container()->bind(CacheItemPoolInterface::class, ([$this, $cacheDriver . 'CachePoolFactory'])());
        }
    }

    /**
     * @return callable
     */
    protected function filesystemCachePoolFactory(): callable
    {
        return function (Filesystem $flysystem) {
            return new FilesystemCachePool($flysystem, 'storage/cache');
        };
    }
}