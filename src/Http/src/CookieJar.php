<?php declare(strict_types = 1);

namespace Venta\Http;

use DateInterval;
use DateTime;
use DateTimeInterface;
use Venta\Contracts\Http\Cookie as CookieContract;
use Venta\Contracts\Http\CookieJar as CookieJarContract;

/**
 * Class CookieJar
 *
 * @package Venta\Http
 */
class CookieJar implements CookieJarContract
{

    /**
     * @var CookieContract[]
     */
    private $cookies = [];

    /**
     * @inheritDoc
     */
    public function add(
        string $name,
        string $value,
        DateTimeInterface $expires,
        string $path = '',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = false
    ) {
        $this->put(new Cookie($name, $value, $expires, $path, $domain, $secure, $httpOnly));
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return $this->cookies;
    }

    /**
     * @inheritDoc
     */
    public function findByName(string $name)
    {
        return $this->cookies[$name] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function forever(
        string $name,
        string $value = null,
        string $path = '',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = false
    ) {
        $this->add($name, $value, (new DateTime())->add(new DateInterval('P10Y')), $path, $domain, $secure, $httpOnly);
    }

    /**
     * @inheritDoc
     */
    public function forget(string $name)
    {
        $this->add($name, '', (new DateTime())->setTimestamp(1));
    }

    /**
     * @inheritDoc
     */
    public function put(CookieContract $cookie)
    {
        $this->cookies[$cookie->name()] = $cookie;
    }

    /**
     * @inheritDoc
     */
    public function session(
        string $name,
        string $value,
        string $path = '',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = false
    ) {
        $this->put(new Cookie($name, $value, null, $path, $domain, $secure, $httpOnly));
    }


}