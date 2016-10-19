<?php declare(strict_types = 1);

namespace Venta\Http;

use Venta\Contracts\Http\Cookie as CookieContract;

/**
 * Class Cookie
 *
 * @package Venta\Http
 */
class Cookie implements CookieContract
{
    protected $domain;

    protected $expire;

    protected $httpOnly;

    protected $name;

    protected $path;

    protected $secure;

    protected $value;

    /**
     * Cookie constructor.
     *
     * @param        $name
     * @param null $value
     * @param int $expire
     * @param string $path
     * @param null $domain
     * @param bool $secure
     * @param bool $httpOnly
     */
    public function __construct(
        $name,
        $value = null,
        $expire = 0,
        $path = '/',
        $domain = null,
        $secure = false,
        $httpOnly = true
    ) {
        if (preg_match("/[=,; \t\r\n\013\014]/", $name)) {
            throw new \InvalidArgumentException(sprintf('The cookie name "%s" contains invalid characters.', $name));
        }

        if (empty($name)) {
            throw new \InvalidArgumentException('The cookie name cannot be empty.');
        }

        $expire = ($expire === "") ? 0 : $expire;
        if ($expire instanceof \DateTimeInterface) {
            $expire = $expire->format('U');
        } elseif (!is_numeric($expire)) {
            $expire = strtotime($expire);

            if (false === $expire || -1 === $expire) {
                throw new \InvalidArgumentException('The cookie expiration time is not valid.');
            }
        }

        $this->name = $name;
        $this->value = $value;
        $this->domain = $domain;
        $this->expire = $expire;
        $this->path = empty($path) ? '/' : $path;
        $this->secure = (bool)$secure;
        $this->httpOnly = (bool)$httpOnly;
    }

    public static function createFromString(string $cookie)
    {
        $cookie = str_replace(' ', '', $cookie);
        $pattern =
            '/^(\w+)=(\w+);(?:expires=([^\s|\;]+);)?(?:path=([^\s|\;]+);)?(?:domain=([^\s|\;]+);)?(?:(secure);)?(httponly)?/';
        preg_match($pattern, $cookie, $result);
        array_shift($result);
        $reflected = new \ReflectionClass(self::class);

        return $reflected->newInstanceArgs($result);
    }

    /**
     * @param $string
     * @return int timestamp
     */
    public static function inDateInterval(string $string)
    {
        return (new \DateTime('now'))->add(new \DateInterval($string))->getTimestamp();
    }

    /**
     * @param $days
     * @return int timestamp
     */
    public static function inDays($days)
    {
        return (new \DateTime('now'))->add(new \DateInterval('P' . $days . 'D'))->getTimestamp();
    }

    /**
     * @param $hours
     * @return int timestamp
     */
    public static function inHours($hours)
    {
        return (new \DateTime('now'))->add(new \DateInterval('PT' . $hours . 'H'))->getTimestamp();
    }

    /**
     * @param $minutes
     * @return int timestamp
     */
    public static function inMinutes($minutes)
    {
        return (new \DateTime('now'))->add(new \DateInterval('PT' . $minutes . 'M'))->getTimestamp();
    }

    /**
     * @param $months
     * @return int timestamp
     */
    public static function inMonths($months)
    {
        return (new \DateTime('now'))->add(new \DateInterval('P' . $months . 'M'))->getTimestamp();
    }

    /**
     * @param $days
     * @return int timestamp
     */
    public static function inWeeks($days)
    {
        $weeks = $days * 7;

        return (new \DateTime('now'))->add(new \DateInterval('P' . $weeks . 'D'))->getTimestamp();
    }

    /**
     * @return int timestamp
     */
    public static function outdated()
    {
        return (new \DateTime('now'))->sub(new \DateInterval('P12M'))->getTimestamp();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        $str = urlencode($this->getName()) . '=';

        if ('' === (string)$this->getValue()) {
            $str .= 'deleted; expires=' . gmdate('D, d-M-Y H:i:s T', static::outdated());
        } else {
            $str .= urlencode($this->getValue());

            if ($this->getExpireTime() !== 0) {
                $str .= '; expires=' . gmdate('D, d-M-Y H:i:s T', $this->getExpireTime());
            }
        }

        if ($this->path) {
            $str .= '; path=' . $this->path;
        }

        if ($this->getDomain()) {
            $str .= '; domain=' . $this->getDomain();
        }

        if (true === $this->isSecure()) {
            $str .= '; secure';
        }

        if (true === $this->isHttpOnly()) {
            $str .= '; httponly';
        }

        return (string)$str;
    }

    public function asPlainText()
    {
        return $this->__toString();
    }

    /**
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @return int|string timestamp
     */
    public function getExpireTime()
    {
        return $this->expire;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isHttpOnly()
    {
        return $this->httpOnly;
    }

    /**
     * @return bool
     */
    public function isSecure()
    {
        return $this->secure;
    }
}