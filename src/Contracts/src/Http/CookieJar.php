<?php declare(strict_types = 1);

namespace Venta\Contracts\Http;

use DateInterval;
use DateTimeInterface;
use Traversable;


/**
 * Interface CookieJar
 *
 * @package Http
 */
interface CookieJar extends Traversable
{

    /**
     * Adds a cookie to the jar.
     *
     * @param string $name
     * @param string $value
     * @param DateTimeInterface|DateInterval|string $expiration
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return void
     */
    public function add(
        string $name,
        string $value,
        $expiration,
        string $path = '',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = false
    );

    /**
     * Returns all cookies.
     *
     * @return Cookie[]
     */
    public function all(): array;

    /**
     * Returns cookie by name
     *
     * @param string $name
     * @return Cookie|null
     */
    public function findByName(string $name);

    /**
     * Puts a cookie with 10-year expiration in the jar.
     *
     * @param string $name
     * @param string|null $value
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return void
     */
    public function forever(
        string $name,
        string $value,
        string $path = '',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = false
    );

    /**
     * Puts an expired cookie in the jar.
     *
     * @param string $name
     * @return void
     */
    public function forget(string $name);

    /**
     * Puts a cookie in the jar.
     *
     * @param Cookie $cookie
     * @return void
     */
    public function put(Cookie $cookie);

    /**
     * Puts session lasting cookie in the jar.
     *
     * @param string $name
     * @param string|null $value
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @return void
     */
    public function session(
        string $name,
        string $value,
        string $path = '',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = false
    );

}