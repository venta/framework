<?php

namespace spec\Venta\Http;

use DateTime;
use PhpSpec\ObjectBehavior;
use Venta\Contracts\Http\Cookie;
use Venta\Contracts\Http\CookieJar;

class CookieJarSpec extends ObjectBehavior
{
    function it_adds_cookie()
    {
        $this->findByName('name')->shouldBeNull();
        $this->add('name', 'value', new DateTime())->shouldBeNull();
        $this->findByName('name')->shouldBeAnInstanceOf(Cookie::class);
    }

    function it_can_expire_forgotten_cookies()
    {
        $this->forget('expired');
        $this->findByName('expired')->shouldBeAnInstanceOf(Cookie::class);
        // todo: add expire check
    }

    function it_can_put_forever_cookie()
    {
        $this->forever('forever', 'value');
        $this->findByName('forever')->shouldBeAnInstanceOf(Cookie::class);
        // todo: add expire check
    }

    function it_can_put_session_cookie()
    {
        $this->session('session', 'value');
        $this->findByName('session')->shouldBeAnInstanceOf(Cookie::class);
        // todo: add expire check
    }

    function it_is_initializable()
    {
        $this->shouldImplement(CookieJar::class);
    }

    function it_puts_cookie(Cookie $cookie)
    {
        $cookie->name()->willReturn('name');
        $this->put($cookie);
        $this->findByName('name')->shouldBe($cookie);
    }

    function it_returns_all_cookies(Cookie $c1, Cookie $c2)
    {
        $c1->name()->willReturn('c1');
        $c2->name()->willReturn('c2');
        $this->put($c1);
        $this->put($c2);
        $this->all()->shouldContain($c1);
        $this->all()->shouldContain($c2);
    }

}
