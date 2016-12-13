<?php

namespace spec\Venta\Http;

use DateTime;
use DateTimeImmutable;
use DateTimeZone;
use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Venta\Contracts\Http\Cookie as CookieContract;

class CookieSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('name', 'value', null, '/path', 'example.com', false, false);
    }

    function it_can_be_http_only()
    {
        $this->shouldNotBeHttpOnly();
        $cookie = $this->httpOnly();
        $cookie->shouldBeHttpOnly();
        $this->shouldNotBeHttpOnly();
    }

    function it_can_be_secured()
    {
        $this->shouldNotBeSecure();
        $cookie = $this->secured();
        $cookie->shouldBeSecure();
        $this->shouldNotBeSecure();
    }

    function it_can_render_empty_value()
    {
        $deleted = $this->withValue('');
        $deleted->__toString()->shouldContain('deleted');
        $deleted->__toString()->shouldContain('01-Jan-1970');
    }

    function it_can_render_to_setcookie_header_string()
    {
        $this->__toString()->shouldContain('name=value');
        $this->__toString()->shouldContain('path=/path');
        $this->__toString()->shouldContain('domain=example.com');
        $this->__toString()->shouldNotContain('expires');
        $this->__toString()->shouldNotContain('secure');
        $this->__toString()->shouldNotContain('httponly');
    }

    function it_can_render_with_secure_httponly_and_expire()
    {
        $now = new DateTime('now', new DateTimeZone('GMT+0000'));
        $cookie = $this->withExpires($now)->secured()->httpOnly();
        $cookie->__toString()->shouldContain('expires=' . $now->format(DateTime::COOKIE));
        $cookie->__toString()->shouldContain('secure');
        $cookie->__toString()->shouldContain('httponly');
    }

    function it_can_urlencode_value()
    {
        $encoded = $this->withValue('value with spaces');
        $encoded->__toString()->shouldContain('name=' . urlencode('value with spaces'));
    }

    function it_fails_with_empty_name()
    {
        $this->beConstructedWith('');
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_fails_with_illegal_chars_in_name()
    {
        $this->beConstructedWith('[=,;');
        $this->shouldThrow(InvalidArgumentException::class)->duringInstantiation();
    }

    function it_has_immutable_domain()
    {
        $this->domain()->shouldBe('example.com');
        $cookie = $this->withDomain('domain');
        $cookie->domain()->shouldBe('domain');
        $this->domain()->shouldBe('example.com');
    }

    function it_has_immutable_expires_datetime()
    {
        $this->expires()->shouldBeNull();
        $dateTime = new DateTime();
        $cookie = $this->withExpires($dateTime);
        $cookie->expires()->shouldBeAnInstanceOf(DateTimeImmutable::class);
        $cookie->expires()->shouldBeLike($dateTime);
        $cookie->expires()->shouldNotBeLike($dateTime->setTimestamp(1));
    }

    function it_has_immutable_path()
    {
        $this->path()->shouldBe('/path');
        $cookie = $this->withPath('/new-path');
        $cookie->path()->shouldBe('/new-path');
        $this->path()->shouldBe('/path');
    }

    function it_has_immutable_value()
    {
        $this->value()->shouldBe('value');
        $cookie = $this->withValue('new value');
        $cookie->value()->shouldBe('new value');
        $this->value()->shouldBe('value');
    }

    function it_is_initializable()
    {
        //$this->beConstructedWith('name', 'value', null, '/path', 'example.com', false, false);
        $this->shouldImplement(CookieContract::class);
        $this->name()->shouldBe('name');
        $this->value()->shouldBe('value');
        $this->expires()->shouldBeNull();
        $this->path()->shouldBe('/path');
        $this->domain()->shouldBe('example.com');
        $this->shouldNotBeSecure();
        $this->shouldNotBeHttpOnly();
    }

}
