<?php declare(strict_types = 1);

namespace Venta\Contracts\Cache;

/**
 * Interface Cache
 * Provides a facade for simplified usage
 * of PSR-6 cache pools and cache items
 *
 * @package Venta\Contracts\Cache
 */
interface Cache
{

    /**
     * Delete key from cache.
     *
     * @param  string $key
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * Get value from cache
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Check if key exists in cache
     *
     * @param  string $key
     * @return bool
     */
    public function has(string $key): bool;

    /**
     * Set value to cache for amount of time
     *
     * @param string $key
     * @param $value
     * @param $expires
     * @return bool
     */
    public function put(string $key, $value, $expires): bool;

    /**
     * Set value to cache
     *
     * @param string $key
     * @param $value
     * @return bool
     */
    public function set(string $key, $value): bool;

}