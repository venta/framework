<?php declare(strict_types = 1);

namespace Abava\Cache\Contact;

/**
 * Interface Cache
 * Provides a facade for simplified usage
 * of PSR-6 cache pools and cache items
 *
 * @package Abava\Cache\Contact
 */
interface Cache
{

    /**
     * Get value from cache
     *
     * @param string $key
     * @return mixed
     */
    public function get(string $key);

    /**
     * Set value to cache
     *
     * @param string $key
     * @param $value
     * @return bool
     */
    public function set(string $key, $value): bool;

    /**
     * Check if key exists in cache
     *
     * @param  string  $key
     * @return bool
     */
    public function has(string $key): bool;


    /**
     * Delete key from cache
     *
     * @param  string $key
     * @return bool
     */
    public function delete(string $key): bool;

    /**
     * Set value to cache for amount of time
     *
     * @param string $key
     * @param $value
     * @param $expires
     * @return bool
     */
    public function put(string $key, $value, $expires):bool;

}