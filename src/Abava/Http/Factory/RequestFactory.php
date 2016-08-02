<?php declare(strict_types = 1);

namespace Abava\Http\Factory;

use Abava\Http\Contract\Request as RequestContract;
use Abava\Http\Contract\RequestFactory as RequestFactoryContract;
use Abava\Http\Request;
use Psr\Http\Message\UriInterface;
use Zend\Diactoros\ServerRequest;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class RequestFactory
 *
 * @package Abava\Http\Factory
 */
class RequestFactory extends ServerRequestFactory implements RequestFactoryContract
{
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
     * Create new \Abava\Http\Request form \Zend\Diactoros\ServerRequest
     * 
     * @param ServerRequest $request
     * @return Request
     */
    protected function createFromBase(ServerRequest $request): Request
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