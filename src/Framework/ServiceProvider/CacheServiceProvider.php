<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use Cache\Adapter\Filesystem\FilesystemCachePool;
use Cache\Adapter\PHPArray\ArrayCachePool;
use InvalidArgumentException;
use League\Flysystem\Filesystem;
use Psr\Cache\CacheItemPoolInterface;
use RuntimeException;
use Throwable;
use Venta\Cache\Cache;
use Venta\Contracts\Cache\Cache as CacheContract;
use Venta\Contracts\Config\Config;
use Venta\Contracts\Debug\ErrorHandler;
use Venta\Contracts\Kernel\Kernel;
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
        $this->container()->bindClass(CacheContract::class, Cache::class);
        $this->tryLoadConfig();
        $config = $this->container()->get(Config::class);
        try {
            if (!isset($config->cache->driver)) {
                throw new RuntimeException('Undefined cache driver.');
            }
            switch ($config->cache->driver) {
                case 'array':
                case 'memory':
                    $this->container()->bindClass(CacheItemPoolInterface::class, ArrayCachePool::class, true);
                    break;
                case 'void':
                case 'null':
                    $this->container()
                        ->bindClass(CacheItemPoolInterface::class, 'Cache\Adapter\Void\VoidCachePool', true);
                    break;
                case 'file':
                case 'files':
                case 'filesystem':
                    $this->container()
                        ->bindFactory(CacheItemPoolInterface::class, $this->filesystemCachePoolFactory(), true);
                    break;
                case 'redis':
                    $this->container()
                        ->bindClass(CacheItemPoolInterface::class, 'Cache\Adapter\Redis\RedisCachePool', true);
                    break;
                case 'predis':
                    $this->container()
                        ->bindClass(CacheItemPoolInterface::class, 'Cache\Adapter\Predis\PredisCachePool', true);
                    break;
                case 'memcached':
                    $this->container()
                        ->bindClass(CacheItemPoolInterface::class, 'Cache\Adapter\Memcached\MemcachedCachePool', true);
                    break;
                default:
                    $this->container()->bindClass(
                        CacheItemPoolInterface::class,
                        ([$this, $config->cache->driver . 'CachePoolFactory'])(),
                        true
                    );
            }
        } catch (Throwable $e) {
            $this->container()->get(ErrorHandler::class)->handleThrowable($e);
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

    /**
     * Tries to load cache config from /src/config folder.
     *
     * @return void
     */
    private function tryLoadConfig()
    {
        try {
            $this->loadConfigFromFiles($this->container()->get(Kernel::class)->rootPath() . '/config/cache.php');
        } catch (Throwable $e) {
            $this->container()->get(ErrorHandler::class)->handleThrowable($e);
        }
    }

}