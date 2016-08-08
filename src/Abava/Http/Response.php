<?php declare(strict_types = 1);

namespace Abava\Http;

use Abava\Http\Contract\Response as ResponseContract;
use Abava\Http\Contract\Cookie as CookieContract;
use Zend\Diactoros\Response as BaseResponse;

/**
 * Class Response
 *
 * @package Abava\Http
 */
class Response extends BaseResponse implements ResponseContract
{
    use ResponseTrait;

    public function addCookie(CookieContract $cookie): ResponseContract
    {
        return $this->addCookieToHeader($cookie);
    }

    /**
     * @param $cookies Cookie[]
     * @return ResponseContract;
     * @throws \InvalidArgumentException
     */
    public function addCookies($cookies): ResponseContract
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
}