<?php declare(strict_types = 1);

namespace Venta\Cache;

use Ds\Map;
use Ds\Vector;
use Psr\Cache\CacheItemInterface;
use Psr\Cache\CacheItemPoolInterface;
use Venta\Cache\Exception\InvalidArgumentException;
use Venta\Cache\Exception\InvalidDriverException;
use Venta\Contracts\Cache\DriverContract;
use Venta\Contracts\Cache\ItemContract;

/**
 * Class Pool
 *
 * @package Venta\Cache
 */
class Pool implements CacheItemPoolInterface
{
    /**
     * Driver instance holder
     *
     * @var DriverContract
     */
    protected $_driver;

    /**
     * Holder for items of deferred save
     *
     * @var Vector
     */
    protected $_deferredQueue;

    /**
     * Construct function
     *
     * @param string $driver
     * @param array $options
     */
    public function __construct(string $driver = 'memory', array $options = [])
    {
        $this->_driver = $this->_createDriver($driver, $options);
        $this->_deferredQueue = new Vector;
    }

    /**
     * {@inheritdoc}
     */
    public function getItem($key): ItemContract
    {
        if (!$this->_keyIsValid($key)) {
            throw new InvalidArgumentException($key);
        }

        if ($this->_driver->instance()->contains($key)) {
            return $this->_driver->instance()->fetch($key);
        }

        return new Item($key, null, null, false);
    }

    /**
     * {@inheritdoc}
     */
    public function getItems(array $keys = [])
    {
        $results = [];

        foreach ($keys as $key) {
            $results[$key] = $this->getItem($key);
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function hasItem($key): bool
    {
        if (!$this->_keyIsValid($key)) {
            throw new InvalidArgumentException($key);
        }

        return $this->_driver->instance()->contains($key);
    }

    /**
     * {@inheritdoc}
     */
    public function clear(): bool
    {
        return $this->_driver->instance()->flushAll();
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItem($key): bool
    {
        if (!$this->_keyIsValid($key)) {
            throw new InvalidArgumentException($key);
        }

        return $this->_driver->instance()->delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteItems(array $keys): bool
    {
        $results = true;

        foreach ($keys as $key) {
            $this->deleteItem($key);
        }

        return $results;
    }

    /**
     * {@inheritdoc}
     */
    public function save(CacheItemInterface $item): bool
    {
        /** @var ItemContract $item */
        return $this->_driver->instance()->save($item->getKey(), $item, $item->getLifetime());
    }

    /**
     * {@inheritdoc}
     */
    public function saveDeferred(CacheItemInterface $item) : bool
    {
        $this->_deferredQueue->push($item);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function commit(): bool
    {
        $result = true;

        foreach ($this->_deferredQueue as $item) {
            $result &= $this->save($item);
        }

        return $result;
    }

    /**
     * Creates driver instance
     *
     * @param  string $driver
     * @param  array  $options
     * @throws InvalidDriverException
     * @return DriverContract
     */
    protected function _createDriver(string $driver, array $options = []): DriverContract
    {
        $class = '\Venta\Cache\Driver\\' . ucfirst($driver) . 'Driver';

        if (!class_exists($class)) {
            throw new InvalidDriverException($class);
        }

        return (new $class)->setOptions($options);
    }

    /**
     * Defines, if cache key is a valid key
     *
     * @param  mixed $key
     * @return bool
     */
    protected function _keyIsValid($key): bool
    {
        return is_string($key);
    }
}