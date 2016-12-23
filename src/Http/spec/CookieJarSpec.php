<?php

namespace spec\Venta\Http;

use DateInterval;
use DateTime;
use DateTimeImmutable;
use InvalidArgumentException;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use stdClass;
use Traversable;
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
        $dateTime = new DateTime;
        $immutableDateTime = new DateTimeImmutable;
        $dateInterval = new DateInterval('P10D');
        $timestamp = time();
        $relative = '+10 days';

        $this->add('dateTime', 'value', $dateTime)->shouldBeNull();
        $this->add('immutableDateTime', 'value', $immutableDateTime)->shouldBeNull();
        $this->add('dateInterval', 'value', $dateInterval)->shouldBeNull();
        $this->add('timestamp', 'value', $timestamp)->shouldBeNull();
        $this->add('relative', 'value', $relative)->shouldBeNull();

        $dateTimeCookie = $this->findByName('dateTime');
        $dateTimeCookie->shouldImplement(Cookie::class);
        $dateTimeCookie->expiration()->shouldBeLike($dateTime);

        $dateTimeCookie = $this->findByName('immutableDateTime');
        $dateTimeCookie->shouldImplement(Cookie::class);
        $dateTimeCookie->expiration()->shouldBeLike($immutableDateTime);

        $dateIntervalCookie = $this->findByName('dateInterval');
        $dateIntervalCookie->shouldImplement(Cookie::class);
        $dateIntervalCookie->expiration()->shouldBeLike((new DateTime())->add($dateInterval));

        $timestampCookie = $this->findByName('timestamp');
        $timestampCookie->shouldImplement(Cookie::class);
        $timestampCookie->expiration()->shouldBeLike(new DateTime("@$timestamp"));

        $relativeCookie = $this->findByName('relative');
        $relativeCookie->shouldImplement(Cookie::class);
        $relativeCookie->expiration()->shouldBeLike(new DateTime($relative));
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

    function it_fails_to_add_cookie_with_invalid_expiration_type()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during('add', ['name', 'value', new stdClass]);
        $this->shouldThrow(InvalidArgumentException::class)->during('add', ['name', 'value', []]);
        $this->shouldThrow()->during('add', ['name', 'value', 'string']);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(CookieJar::class);
    }

    function it_is_traversable()
    {
        $this->getIterator()->shouldImplement(Traversable::class);
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
