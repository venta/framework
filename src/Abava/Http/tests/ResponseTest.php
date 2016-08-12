<?php

use PHPUnit\Framework\TestCase;
use Abava\Http\Cookie;

/**
 * Class ResponseTest
 */
class ResponseTest extends TestCase
{
    /**
     * @test
     */
    public function implementsResponseContract()
    {
        $this->assertInstanceOf(\Abava\Http\Contract\Response::class, new \Abava\Http\Response);
    }

    /**
     * @test
     */
    public function canAppendStringToBody()
    {
        $response = new \Abava\Http\Response();
        $this->assertEmpty($response->getBody()->__toString());
        $result = $response->append('abc');
        $this->assertInstanceOf(\Abava\Http\Contract\Response::class, $result);
        $this->assertSame($response, $result);
        $this->assertSame($response->getBody(), $result->getBody());
        $this->assertContains('abc', $response->getBody()->__toString());
        $this->assertContains('abc', $result->getBody()->__toString());
    }

    /**
     * @test
     */
    public function canGetBodyContent()
    {
        $response = new \Abava\Http\Response();
        $this->assertEmpty($response->getContent());
        $string = "Let's test";
        $response->append($string);
        $this->assertSame($response->getContent(), $response->getBody()->__toString());
        $this->assertSame($response->getContent(), $string);
    }

    /**
     * @test
     */
    public function canSetSingleCookie()
    {
        $response = new \Abava\Http\Response();
        $this->assertEmpty($response->getHeader('Set-Cookie'));
        $cookie = new \Abava\Http\Cookie('name', 'value');
        $response = $response->addCookie($cookie);
        $this->assertCount(1, $response->getHeader('Set-Cookie'));
    }

    /**
     * @test
     */
    public function canSetMultipleCookies()
    {
        $response = new \Abava\Http\Response();
        $this->assertEmpty($response->getHeader('Set-Cookie'));
        $cookie = new \Abava\Http\Cookie('name', 'value');
        $cookie2 = new \Abava\Http\Cookie('name2', 'value2');
        $response = $response->addCookies([$cookie, $cookie2]);
        $this->assertCount(2, $response->getHeader('Set-Cookie'));
    }

    /**
     * @test
     */
    public function canSetMultipleCookiesFromObj()
    {
        $response = new \Abava\Http\Response();
        $arrayIterator = new class() extends \ArrayIterator
        {
        };
        $response->addCookies($arrayIterator);
    }

    /**
     * @test
     */
    public function failMultipleCookiesAdding()
    {
        $response = new \Abava\Http\Response();
        $this->expectException("InvalidArgumentException");
        $response = $response->addCookies('cookie');
    }

    /**
     * @test
     */
    public function getCookieFromEmptyContainer()
    {
        $response = new \Abava\Http\Response();
        $cookies = $response->getCookies();
        $this->assertNull($cookies);
    }

    /**
     * @test
     */
    public function getPlainTextCookies()
    {
        $response = new \Abava\Http\Response();
        $cookies = [new Cookie('name', 'value'),
            new Cookie('cookie', 'cookievalue')];
        $response = $response->addCookies($cookies);
        $attachedCookies = $response->getCookies();
        $this->assertCount(count($cookies), $attachedCookies);
        for ($i = 0; $i < count($cookies); $i++) {
            $this->assertSame((string)$cookies[$i], $attachedCookies[$i]);
        }
    }

    /**
     * @test
     */
    public function getCookiesAsObjects()
    {
        $response = new \Abava\Http\Response();
        $cookies = [new Cookie('name', 'value'),
            new Cookie('cookie', 'cookievalue')];
        $response = $response->addCookies($cookies);
        $attachedCookies = $response->getCookies(true);
        foreach ($attachedCookies as $cookie) {
            $this->assertInstanceOf(Cookie::class, $cookie);
        }
    }

    /**
     * @test
     */
    public function getPlainCookieByName()
    {
        $response = new \Abava\Http\Response();
        $cookies = [new Cookie('name', 'value'),
            new Cookie('cookie', 'cookievalue')];
        $response = $response->addCookies($cookies);
        $cookie = $response->getCookie('cookie');
        $this->assertSame((string)$cookies[1], $cookie);
    }

    /**
     * @test
     */
    public function getNonExistingCookieByName()
    {
        $response = new \Abava\Http\Response();
        $cookies = [new Cookie('name', 'value'),
            new Cookie('cookie', 'cookievalue')];
        $response = $response->addCookies($cookies);
        $cookie = $response->getCookie('notexisting');
        $this->assertNull($cookie);
    }

    /**
     * @test
     */
    public function getSingleCookieWhenNoCookieWasSet()
    {
        $response = new \Abava\Http\Response();
        $this->assertNull($response->getCookies());
        $cookie = $response->getCookie('notexisting');
        $this->assertNull($cookie);
    }
}