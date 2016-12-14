<?php

namespace spec\Venta\Http;

use DateTime;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use Venta\Contracts\Http\Cookie;
use Venta\Contracts\Http\CookieJar;

class CookieJarSpec extends ObjectBehavior
{
    public function getMatchers()
    {
        $dateFormat = 'Y-m-d H:i:s';

        return [
            'beOlder' => function ($subject, DateTime $dateTime) use ($dateFormat) {
                if ($subject >= $dateTime) {
                    throw new FailureException(
                        sprintf(
                            'The provided datetime ("%s") expected to be older than "%s", but it is not.',
                            $subject->format($dateFormat), $dateTime->format($dateFormat)
                        )
                    );
                }

                return true;
            },

            'beNewer' => function ($subject, DateTime $dateTime) use ($dateFormat) {
                if ($subject <= $dateTime) {
                    throw new FailureException(
                        sprintf(
                            'The provided datetime ("%s") expected to be newer than "%s", but it is not.',
                            $subject->format($dateFormat), $dateTime->format($dateFormat)
                        )
                    );
                }

                return true;
            },
        ];
    }

    function it_adds_cookie()
    {
        $this->findByName('name')->shouldBeNull();
        $this->add('name', 'value', new DateTime())->shouldBeNull();
        $this->findByName('name')->shouldImplement(Cookie::class);
    }

    function it_can_expire_forgotten_cookies()
    {
        $this->forget('expired');
        $cookie = $this->findByName('expired');
        $cookie->shouldImplement(Cookie::class);
        $cookie->expiration()->shouldBeOlder(new DateTime);
    }

    function it_can_put_forever_cookie()
    {
        $this->forever('forever', 'value');
        $cookie = $this->findByName('forever');
        $cookie->shouldImplement(Cookie::class);
        $cookie->expiration()->shouldBeNewer(new DateTime('+5 years'));
    }

    function it_can_put_session_cookie()
    {
        $this->session('session', 'value');
        $cookie = $this->findByName('session');
        $cookie->shouldImplement(Cookie::class);
        $cookie->expiration()->shouldBeNull();
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
