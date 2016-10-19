<?php declare(strict_types = 1);

namespace Venta\Http\Factory;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Venta\Contracts\Http\Request as RequestContract;
use Venta\Contracts\Http\RequestFactory as RequestFactoryContract;
use Venta\Http\Request;
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
        if ($uri instanceof UriInterface) {
            return (new Request([], [], null, $method))
                ->withUri($uri);
        }

        return new Request([], [], $uri, $method);
    }

    /**
     * Create a new server request from PHP globals.
     *
     * @return RequestContract
     */
    public function createServerRequestFromGlobals(): RequestContract
    {
        return $this->createFromBase(parent::fromGlobals());
    }

    /**
     * Create new \Venta\Http\Request form Psr\Http\Message\ServerRequestInterface
     *
     * @param ServerRequestInterface $request
     * @return RequestContract
     */
    protected function createFromBase(ServerRequestInterface $request): RequestContract
    {
        return new Request(
            $request->getServerParams(),
            $request->getUploadedFiles(),
            $request->getUri(),
            $request->getMethod(),
            $request->getBody(),
            $request->getHeaders(),
            $request->getCookieParams(),
            $request->getQueryParams(),
            $request->getParsedBody(),
            $request->getProtocolVersion()
        );
    }
}