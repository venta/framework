<?php declare(strict_types = 1);

namespace Venta\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Venta\Contracts\Http\Request as RequestContract;
use Venta\Contracts\Routing\Route;

/**
 * Class Request
 *
 * @package Venta\Http
 */
class Request implements RequestContract
{
    /**
     * @var ServerRequestInterface
     */
    private $psrRequest;

    /**
     * Request constructor.
     *
     * @param ServerRequestInterface $psrRequest
     */
    public function __construct(ServerRequestInterface $psrRequest)
    {
        $this->psrRequest = $psrRequest;
    }

    /**
     * @inheritDoc
     */
    public function getAttribute($name, $default = null)
    {
        return $this->psrRequest->getAttribute($name, $default);
    }

    /**
     * @inheritDoc
     */
    public function getAttributes()
    {
        return $this->psrRequest->getAttributes();
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        return $this->psrRequest->getBody();
    }

    /**
     * @inheritDoc
     */
    public function getCookieParams()
    {
        return $this->psrRequest->getCookieParams();
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name)
    {
        return $this->psrRequest->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name)
    {
        return $this->psrRequest->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaders()
    {
        return $this->psrRequest->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function getMethod()
    {
        return $this->psrRequest->getMethod();
    }

    /**
     * @inheritDoc
     */
    public function getParsedBody()
    {
        return $this->psrRequest->getParsedBody();
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion()
    {
        return $this->psrRequest->getProtocolVersion();
    }

    /**
     * @inheritDoc
     */
    public function getQueryParams()
    {
        return $this->psrRequest->getQueryParams();
    }

    /**
     * @inheritDoc
     */
    public function getRequestTarget()
    {
        return $this->psrRequest->getRequestTarget();
    }

    /**
     * @inheritDoc
     */
    public function route(): Route
    {
        return $this->getAttribute('route');
    }

    /**
     * @inheritDoc
     */
    public function getServerParams()
    {
        return $this->psrRequest->getServerParams();
    }

    /**
     * @inheritDoc
     */
    public function getUploadedFiles()
    {
        return $this->psrRequest->getUploadedFiles();
    }

    /**
     * @inheritDoc
     */
    public function getUri()
    {
        return $this->psrRequest->getUri();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name)
    {
        return $this->psrRequest->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value)
    {
        return new self($this->psrRequest->withAddedHeader($name, $value));
    }

    /**
     * @inheritDoc
     */
    public function withAttribute($name, $value)
    {
        return new self($this->psrRequest->withAttribute($name, $value));
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body)
    {
        return new self($this->psrRequest->withBody($body));
    }

    /**
     * @inheritDoc
     */
    public function withCookieParams(array $cookies)
    {
        return new self($this->psrRequest->withCookieParams($cookies));
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
        return new self($this->psrRequest->withHeader($name, $value));
    }

    /**
     * @inheritDoc
     */
    public function withMethod($method)
    {
        return new self($this->psrRequest->withMethod($method));
    }

    /**
     * @inheritDoc
     */
    public function withParsedBody($data)
    {
        return new self($this->psrRequest->withParsedBody($data));
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version)
    {
        return new self($this->psrRequest->withProtocolVersion($version));
    }

    /**
     * @inheritDoc
     */
    public function withQueryParams(array $query)
    {
        return new self($this->psrRequest->withQueryParams($query));
    }

    /**
     * @inheritDoc
     */
    public function withRequestTarget($requestTarget)
    {
        return new self($this->psrRequest->withRequestTarget($requestTarget));
    }

    /**
     * @inheritDoc
     */
    public function withRoute(Route $route): RequestContract
    {
        return $this->withAttribute('route', $route);
    }

    /**
     * @inheritDoc
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        return new self($this->psrRequest->withUploadedFiles($uploadedFiles));
    }

    /**
     * @inheritDoc
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return new self($this->psrRequest->withUri($uri, $preserveHost));
    }

    /**
     * @inheritDoc
     */
    public function withoutAttribute($name)
    {
        return new self($this->psrRequest->withoutAttribute($name));
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name)
    {
        return new self($this->psrRequest->withoutHeader($name));
    }

}