<?php

use PHPUnit\Framework\TestCase;
use Abava\Http\Cookie;

/**
 * Class CookieTest
 */
class CookieTest extends TestCase
{
    /**
     * @test
     */
    public function cookieIsConstructed()
    {
        $this->assertInstanceOf(\Abava\Http\Contract\Cookie::class, new Cookie('name', 'value'));
    }

    /**
     * @test
     */
    public function emptyNameException()
    {
        $this->expectException("InvalidArgumentException");
        $cookie = new Cookie('', 'value');
    }

    /**
     * @test
     */
    public function invalidNameException()
    {
        $this->expectException("InvalidArgumentException");
        $cookie = new Cookie('n,m   e', 'value');
    }

    /**
     * @test
     */
    public function datetimeToTimestamp()
    {
        $now = new DateTime();
        $cookie = new Cookie('name', 'value', $now);
        $this->assertEquals($now->getTimestamp(), $cookie->getExpireTime());
    }

    /**
     * @test
     */
    public function stringToTimestamp()
    {
        $datetime = '22.07.2015 14:14:14';
        $cookie = new Cookie('name', 'value', $datetime);
        $this->assertEquals(strtotime($datetime), $cookie->getExpireTime());
    }

    /**
     * @test
     */
    public function incorrectStringDate()
    {
        $datetime = '16.16.16 14:14:14';
        $this->expectException("InvalidArgumentException");
        new Cookie('name', 'value', $datetime);
    }

    /**
     * @test
     */
    public function cookieToString()
    {
        $cookie = new Cookie('name', 'value');
        $expectedString = "name=value; path=/; httponly";
        $this->assertSame((string)$cookie, $expectedString);
    }

    /**
     * @test
     */
    public function isCorrectlyOutdated()
    {
        $this->assertGreaterThanOrEqual(Cookie::outdated(), (new DateTime())->getTimestamp());
    }

    /**
     * @test
     */
    public function emptyValueDeletesCookie()
    {
        $cookie = new Cookie('name', '');
        $cookieString = (string)$cookie;
        preg_match('/.*expires=(\d+);.*/', $cookieString, $res);
        $this->assertGreaterThanOrEqual((int)$res[1], (new DateTime())->getTimestamp());
    }

    /**
     * @test
     */
    public function expirationTimeIsSet()
    {
        $timestamp = (new DateTime())->add(new \DateInterval('P1D'))->getTimestamp();
        $cookie = new Cookie('name', 'value', $timestamp);
        preg_match('/.*expires=(\d+);.*/', (string)$cookie, $res);
        $this->assertEquals($res[1], $timestamp);
    }

    /**
     * @test
     */
    public function domainIsSet()
    {
        $cookie = new Cookie('name', 'value', Cookie::inMinutes(5), '/', 'domain.com');
        $this->assertRegExp('/.*domain=domain.com.*/', (string) $cookie);
    }

    /**
     * @test
     */
    public function secureFlagEnableDisable()
    {
        $cookie = new Cookie('name', 'value', Cookie::inMinutes(5), '/', 'domain.com', false);
        $this->assertRegExp('/.*^(secure)*/', (string) $cookie);
        $cookie = new Cookie('name', 'value', Cookie::inMinutes(5), '/', 'domain.com', true);
        $this->assertRegExp('/.*secure*/', (string) $cookie);
    }

    /**
     * @test
     */
    public function disableHttpOnly()
    {
        $cookie = new Cookie('name', 'value', Cookie::inMinutes(5), '/', 'domain.com', false, false);
        $this->assertRegExp('/.*^(httponly)*/', (string) $cookie);
    }

    /**
     * @test
     */
    public function inMinutes()
    {
        $this->assertGreaterThanOrEqual(Cookie::inMinutes(5), (new DateTime())->add(new \DateInterval('PT5M2S'))->getTimestamp());
        $this->assertLessThanOrEqual(Cookie::inMinutes(5), (new DateTime())->add(new \DateInterval('PT4M58S'))->getTimestamp());
    }

    /**
     * @test
     */
    public function inHours()
    {
        $this->assertGreaterThanOrEqual(Cookie::inHours(8), (new DateTime())->add(new \DateInterval('PT8H0M2S'))->getTimestamp());
        $this->assertLessThanOrEqual(Cookie::inHours(8), (new DateTime())->add(new \DateInterval('PT7H59M58S'))->getTimestamp());
    }

    /**
     * @test
     */
    public function inDays()
    {
        $this->assertGreaterThanOrEqual(Cookie::inDays(3), (new DateTime())->add(new \DateInterval('P3DT0H0M2S'))->getTimestamp());
        $this->assertLessThanOrEqual(Cookie::inDays(3), (new DateTime())->add(new \DateInterval('P2DT23H59M58S'))->getTimestamp());
    }

    /**
     * @test
     */
    public function inWeeks()
    {
        $this->assertGreaterThanOrEqual(Cookie::inWeeks(3), (new DateTime())->add(new \DateInterval('P3WT0H0M2S'))->getTimestamp());
        $this->assertLessThanOrEqual(Cookie::inWeeks(3), (new DateTime())->add(new \DateInterval('P2W6DT23H59M58S'))->getTimestamp());
    }

    /**
     * @test
     */
    public function inMonths()
    {
        $this->assertGreaterThanOrEqual(Cookie::inMonths(3), (new DateTime())->add(new \DateInterval('P4MT0H0M2S'))->getTimestamp());
        $this->assertLessThanOrEqual(Cookie::inMonths(3), (new DateTime())->add(new \DateInterval('P2M3W6DT23H59M58S'))->getTimestamp());
    }

    /**
     * @test
     */
    public function inDateInterval()
    {
        $this->assertGreaterThanOrEqual(Cookie::inDateInterval('P2DT2H'), (new DateTime())->add(new \DateInterval('P2DT2H2S'))->getTimestamp());
        $this->assertLessThanOrEqual(Cookie::inDateInterval('P2DT2H'), (new DateTime())->add(new \DateInterval('P1DT59M58S'))->getTimestamp());
    }
}