<?php declare(strict_types = 1);

namespace Venta\Cache\Driver;

use Doctrine\Common\Cache\ArrayCache;
use Doctrine\Common\Cache\CacheProvider;
use Venta\Contracts\Cache\DriverContract;

/**
 * Class MemoryDriver
 *
 * @package Venta\Cache\Driver
 */
class MemoryDriver implements DriverContract
{
    /**
     * Real driver holder
     *
     * @var ArrayCache
     */
    protected $_driver;

    /**
     * Construct function
     */
    public function __construct()
    {
        $this->_driver = new ArrayCache;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options = [])
    {
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function instance(): CacheProvider
    {
        return $this->_driver;
    }
}