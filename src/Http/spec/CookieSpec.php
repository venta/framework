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
        $this->beConstructedWith('name');
    }

    function it_can_be_deleted()
    {
        $deleted = $this->withValue('');
        $deleted->__toString()->shouldContain('deleted');
        $deleted->__toString()->shouldContain('01-Jan-1970');
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

    function it_is_immutable()
    {
        $dateTime = new DateTime();
        $cookie = $this
            ->withPath('/new-path')
            ->withValue('new-value')
            ->withDomain('domain.com')
            ->withExpiration($dateTime)
            ->httpOnly()
            ->secured();

        $cookie->path()->shouldBe('/new-path');
        $cookie->value()->shouldBe('new-value');
        $cookie->domain()->shouldBe('domain.com');
        $cookie->expiration()->shouldBeAnInstanceOf(DateTimeImmutable::class);
        $cookie->expiration()->shouldBeLike($dateTime);
        $cookie->expiration()->shouldNotBeLike($dateTime->setTimestamp(1));
        $cookie->shouldBeHttpOnly();
        $cookie->shouldBeSecure();


        $this->path()->shouldNotBe('/new-path');
        $this->value()->shouldNotBe('/new-value');
        $this->domain()->shouldNotBe('domain.com');
        $this->expiration()->shouldNotBeLike($dateTime);
        $this->shouldNotBeHttpOnly();
        $this->shouldNotBeSecure();

    }

    function it_is_initializable()
    {
        $this->beConstructedWith('name', 'value', null, '/path', 'example.com', false, false);

        $this->shouldImplement(CookieContract::class);
        $this->name()->shouldBe('name');
        $this->value()->shouldBe('value');
        $this->expiration()->shouldBeNull();
        $this->path()->shouldBe('/path');
        $this->domain()->shouldBe('example.com');
        $this->shouldNotBeSecure();
        $this->shouldNotBeHttpOnly();
    }

    function it_renders_correct_setcookie_header_value()
    {
        $this->beConstructedWith('name', 'value', null, '/path', 'example.com', false, false);

        $this->__toString()->shouldContain('name=value');
        $this->__toString()->shouldContain('path=/path');
        $this->__toString()->shouldContain('domain=example.com');
        $this->__toString()->shouldNotContain('expires');
        $this->__toString()->shouldNotContain('secure');
        $this->__toString()->shouldNotContain('httponly');

        $now = new DateTime('now', new DateTimeZone('GMT+0000'));
        $cookie = $this->withExpiration($now)->secured()->httpOnly();
        $cookie->__toString()->shouldContain('expires=' . $now->format(DateTime::COOKIE));
        $cookie->__toString()->shouldContain('secure');
        $cookie->__toString()->shouldContain('httponly');

    }

}
