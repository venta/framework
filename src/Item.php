<?php declare(strict_types = 1);

namespace Venta\Cache;

use DateInterval;
use DateTimeInterface;
use Venta\Contracts\Cache\ItemContract;

/**
 * Class CacheItem
 *
 * @package Venta\Cache
 */
class Item implements ItemContract
{
    /**
     * Item key holder
     *
     * @var string
     */
    protected $_key;

    /**
     * Item value holder
     *
     * @var mixed
     */
    protected $_value;

    /**
     * Defines, if item was hit
     *
     * @var bool
     */
    protected $_isHit = true;

    /**
     * Defines, when item expires
     *
     * @var null|DateTimeInterface
     */
    protected $_expiresAt = null;

    /**
     * Lifetime
     *
     * @var int|null
     */
    protected $_lifetime;

    /**
     * Construct function
     *
     * @param  string   $key
     * @param  mixed    $value
     * @param  int|null $lifetime
     * @param  bool     $isHit
     */
    public function __construct(string $key, $value, int $lifetime = null, bool $isHit = true)
    {
        $this->_key = $key;
        $this->_value = $value;
        $this->_isHit = $isHit;

        $this->expiresAfter($lifetime);
    }

    /**
     * {@inheritdoc}
     */
    public function getKey(): string
    {
        return $this->_key;
    }

    /**
     * {@inheritdoc}
     */
    public function getLifetime()
    {
        return $this->_lifetime;
    }

    /**
     * {@inheritdoc}
     */
    public function get()
    {
        if ($this->isHit() === false) {
            return null;
        }

        return $this->_value;
    }

    /**
     * {@inheritdoc}
     */
    public function isHit(): bool
    {
        return $this->_isHit;
    }

    /**
     * {@inheritdoc}
     */
    public function set($value)
    {
        $this->_value = $value;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAt($expiration)
    {
        if ($expiration instanceof DateTimeInterface) {
            $this->_expiresAt = $expiration;
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function expiresAfter($time)
    {
        if ($time instanceof DateInterval) {
            $time = $time->format('s');
        }

        if (!is_int($time)) {
            $time = null;
        }

        $this->_lifetime = $time;

        return $this;
    }
}