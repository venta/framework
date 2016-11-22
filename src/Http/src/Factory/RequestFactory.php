<?php declare(strict_types = 1);

namespace Venta\Http\Factory;

use Psr\Http\Message\UriInterface;
use Venta\Contracts\Http\Request as RequestContract;
use Venta\Contracts\Http\RequestFactory as RequestFactoryContract;
use Venta\Http\Request;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class RequestFactory
 *
 * @package Venta\Http\Factory
 */
class RequestFactory extends ServerRequestFactory implements RequestFactoryContract
{
    /**
     * Create a new server request.
     *
     * @param string $method
     * @param UriInterface|string $uri
     *
     * @return RequestContract
     */
    public function createServerRequest($method, $uri): RequestContract
    {
        return new Request(new ServerRequest([], [], $uri, $method));
    }

    /**
     * Create a new server request from PHP globals.
     *
     * @return RequestContract
     */
    public function createServerRequestFromGlobals(): RequestContract
    {
        return new Request(parent::fromGlobals());
    }
}