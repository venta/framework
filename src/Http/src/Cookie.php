<?php declare(strict_types = 1);

namespace Venta\Http;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;
use Venta\Contracts\Http\Cookie as CookieContract;

/**
 * Class Cookie
 *
 * @package Venta\Http
 */
class Cookie implements CookieContract
{

    /**
     * @var string
     */
    private $domain = '';

    /**
     * @var DateTimeInterface|null
     */
    private $expires = null;

    /**
     * @var bool
     */
    private $httpOnly = false;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $path = '';

    /**
     * @var bool
     */
    private $secure = false;

    /**
     * @var string
     */
    private $value = '';

    /**
     * Cookie constructor.
     *
     * @param string $name
     * @param string $value
     * @param DateTimeInterface|null $expires
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httpOnly
     * @throws InvalidArgumentException
     */
    public function __construct(
        string $name,
        string $value = null,
        DateTimeInterface $expires = null,
        string $path = '',
        string $domain = '',
        bool $secure = false,
        bool $httpOnly = false
    ) {
        if (empty($name)) {
            throw new InvalidArgumentException('The cookie name cannot be empty.');
        }

        if (preg_match("/[=,; \t\r\n\013\014]/", $name)) {
            throw new InvalidArgumentException(sprintf('The cookie name "%s" contains invalid characters.', $name));
        }

        $this->name = $name;
        $this->value = $value;
        $this->domain = $domain;
        $this->expires = $expires ? $this->lockExpires($expires) : null;
        $this->path = $path;
        $this->secure = $secure;
        $this->httpOnly = $httpOnly;
    }

    /**
     * @inheritDoc
     */
    public function __toString()
    {
        $attributes = [];
        $name = urlencode($this->name);
        if ((string)$this->value === '') {
            // Cookie with empty value assumed expired.
            $value = 'deleted';
            $attributes[] = 'expires=' . (new DateTime())->setTimestamp(1)->format(DateTime::COOKIE);
        } else {
            $value = urlencode($this->value);
        }

        // Create cookie crumb with name and value.
        $cookieCrumb = "$name=$value";

        // Add cookie attributes.
        if ($this->expires) {
            $attributes[] = 'expires=' . $this->expires->format(DateTime::COOKIE);
        }
        if ($this->path) {
            $attributes[] = "path={$this->path}";
        }
        if ($this->domain) {
            $attributes[] = "domain={$this->domain}";
        }
        if ($this->secure) {
            $attributes[] = 'secure';
        }
        if ($this->httpOnly) {
            $attributes[] = 'httponly';
        }

        if (count($attributes) > 0) {
            // Add attributes to cookie crumb.
            $cookieCrumb .= '; ' . implode('; ', $attributes);
        }

        return $cookieCrumb;
    }

    /**
     * @inheritDoc
     */
    public function domain(): string
    {
        return $this->domain;
    }

    /**
     * @inheritDoc
     */
    public function expires()
    {
        return $this->expires;
    }

    /**
     * @inheritDoc
     */
    public function httpOnly(): CookieContract
    {
        $cookie = clone $this;
        $cookie->httpOnly = true;

        return $cookie;
    }

    /**
     * @inheritDoc
     */
    public function isHttpOnly(): bool
    {
        return $this->httpOnly;
    }

    /**
     * @inheritDoc
     */
    public function isSecure(): bool
    {
        return $this->secure;
    }

    /**
     * @inheritDoc
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return $this->path;
    }

    /**
     * @inheritDoc
     */
    public function secured(): CookieContract
    {
        $cookie = clone $this;
        $cookie->secure = true;

        return $cookie;
    }

    /**
     * @inheritDoc
     */
    public function value(): string
    {
        return $this->value;
    }

    /**
     * @inheritDoc
     */
    public function withDomain(string $domain): CookieContract
    {
        $cookie = clone $this;
        $cookie->domain = $domain;

        return $cookie;
    }

    /**
     * @inheritDoc
     */
    public function withExpires(DateTimeInterface $expires = null): CookieContract
    {
        $cookie = clone $this;
        $cookie->expires = $expires ? $cookie->lockExpires($expires) : null;

        return $cookie;
    }

    /**
     * @inheritDoc
     */
    public function withPath(string $path): CookieContract
    {
        $cookie = clone $this;
        $cookie->path = $path;

        return $cookie;
    }

    /**
     * @inheritDoc
     */
    public function withValue(string $value): CookieContract
    {
        $cookie = clone $this;
        $cookie->value = $value;

        return $cookie;
    }

    /**
     * Creates immutable date time instance from provided one.
     *
     * @param DateTimeInterface $expires
     * @return DateTimeImmutable
     */
    private function lockExpires(DateTimeInterface $expires): DateTimeImmutable
    {
        if (!$expires instanceof DateTimeImmutable) {
            $expires = new DateTimeImmutable($expires->format(DateTime::ISO8601), $expires->getTimezone());
        }

        return $expires;
    }
}