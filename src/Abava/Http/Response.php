<?php declare(strict_types = 1);

namespace Abava\Http;

use Abava\Http\Contract\Response as ResponseContract;
use Abava\Http\Contract\Cookie as CookieContract;
use MongoDB\BSON\Regex;
use Zend\Diactoros\Response as BaseResponse;

/**
 * Class Response
 *
 * @package Abava\Http
 */
class Response extends BaseResponse implements ResponseContract
{
    use ResponseTrait;

    public function addCookie(CookieContract $cookie)
    {
        return $this->addCookieToHeader($cookie);
    }

    /**
     * @param $cookies Cookie[]
     * @return ResponseContract|RequestContract;
     * @throws \InvalidArgumentException
     */

    public function addCookies($cookies)
    {
        if (!is_array($cookies) && !$cookies instanceof \Traversable) {
            throw new \InvalidArgumentException('Array elements must implement Cookie contract');
        }

        /** @var $response Response * $this is immutable */
        $response = clone $this;
        /** @var $cookie Cookie */
        foreach ($cookies as $cookie) {
            $response = $response->addCookieToHeader($cookie);
        }

        return $response;
    }

    protected function addCookieToHeader(Cookie $cookie)
    {
        return $this->withAddedHeader('Set-Cookie', $cookie->asPlainText());
    }

    /**
     * Get all cookies set to response
     *
     * @param $returnObject bool
     * @return null|string[]|Cookie[]
     */
    public function getCookies(bool $returnObject = false)
    {
        if (!$this->getHeader('set-cookie')) {
            return null;
        }
        if (false === $returnObject) {
            return $this->getHeader('set-cookie');
        }
        $cookieObjects = array();
        foreach ($this->getHeader('set-cookie') as $cookie) {
            $cookieObjects[] = Cookie::createFromString($cookie);
        }

        return $cookieObjects;
    }

    /**
     * Get a single cookie by name
     *
     * @param string $name
     * @param bool $returnObject
     * @return null|string[]
     */
    public function getCookie(string $name, bool $returnObject = false)
    {
        if (!$cookies = $this->getCookies()) {
            return null;
        }
        $pattern = '/(^' . $name . '=.*)/';
        $cookie = implode(preg_grep($pattern, $cookies));
        if ($cookie == '') {
            return null;
        }

        return $returnObject ? Cookie::createFromString($cookie) : $cookie;
    }
}