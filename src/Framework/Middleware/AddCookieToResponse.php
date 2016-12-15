<?php declare(strict_types = 1);

namespace Venta\Framework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Http\CookieJar;
use Venta\Contracts\Routing\Delegate;
use Venta\Contracts\Routing\Middleware;

/**
 * Class AddCookieToResponse
 *
 * @package Middleware
 */
final class AddCookieToResponse implements Middleware
{

    /**
     * @var CookieJar
     */
    private $cookies;

    /**
     * AddCookieToResponse constructor.
     *
     * @param CookieJar $cookies
     */
    public function __construct(CookieJar $cookies)
    {
        $this->cookies = $cookies;
    }

    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request, Delegate $delegate): ResponseInterface
    {
        $response = $delegate->next($request);
        foreach ($this->cookies as $cookie) {
            $response = $response->withAddedHeader('set-cookie', (string)$cookie);
        }

        return $response;
    }


}