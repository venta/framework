<?php declare(strict_types = 1);

namespace Venta\Contracts\Http;

use DateTimeImmutable;
use DateTimeInterface;

/**
 * Interface Cookie
 *
 * @package Venta\Contracts\Http
 */
interface Cookie
{

    /**
     * Returns string representation suitable for HTTP response header.
     *
     * @return string
     */
    public function __toString();

    /**
     * @return string
     */
    public function domain(): string;

    /**
     * @return DateTimeImmutable|null
     */
    public function expiration();

    /**
     * @return Cookie
     * @internal param bool $httpOnly
     */
    public function httpOnly(): Cookie;

    /**
     * @return bool
     */
    public function isHttpOnly(): bool;

    /**
     * @return bool
     */
    public function isSecure(): bool;

    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return string
     */
    public function path(): string;

    /**
     * @return Cookie
     * @internal param bool $secure
     */
    public function secured(): Cookie;

    /**
     * @return string
     */
    public function value(): string;

    /**
     * @param string $domain
     * @return Cookie
     */
    public function withDomain(string $domain): Cookie;

    /**
     * @param DateTimeInterface $expiration
     * @return Cookie
     */
    public function withExpiration(DateTimeInterface $expiration): Cookie;

    /**
     * @param string $path
     * @return Cookie
     */
    public function withPath(string $path): Cookie;

    /**
     * @param string $value
     * @return Cookie
     */
    public function withValue(string $value): Cookie;
}