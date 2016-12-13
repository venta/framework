<?php declare(strict_types = 1);

namespace Venta\Http;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Venta\Contracts\Http\Response as ResponseContract;

/**
 * Class Response
 *
 * @package Venta\Http
 */
class Response implements ResponseContract
{

    /**
     * @var ResponseInterface
     */
    private $psrResponse;

    /**
     * Response constructor.
     *
     * @param ResponseInterface $psrResponse
     */
    public function __construct(ResponseInterface $psrResponse)
    {
        $this->psrResponse = $psrResponse;
    }

    /**
     * @inheritDoc
     */
    public function append(string $body): ResponseContract
    {
        $this->psrResponse->getBody()->write($body);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getBody()
    {
        return $this->psrResponse->getBody();
    }

    /**
     * @inheritDoc
     */
    public function content(): string
    {
        return (string)$this->psrResponse->getBody();
    }

    /**
     * @inheritDoc
     */
    public function getHeader($name)
    {
        return $this->psrResponse->getHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaderLine($name)
    {
        return $this->psrResponse->getHeaderLine($name);
    }

    /**
     * @inheritDoc
     */
    public function getHeaders()
    {
        return $this->psrResponse->getHeaders();
    }

    /**
     * @inheritDoc
     */
    public function getProtocolVersion()
    {
        return $this->psrResponse->getProtocolVersion();
    }

    /**
     * @inheritDoc
     */
    public function getReasonPhrase()
    {
        return $this->psrResponse->getReasonPhrase();
    }

    /**
     * @inheritDoc
     */
    public function getStatusCode()
    {
        return $this->psrResponse->getStatusCode();
    }

    /**
     * @inheritDoc
     */
    public function hasHeader($name)
    {
        return $this->psrResponse->hasHeader($name);
    }

    /**
     * @inheritDoc
     */
    public function withAddedHeader($name, $value)
    {
        return new self($this->psrResponse->withAddedHeader($name, $value));
    }

    /**
     * @inheritDoc
     */
    public function withBody(StreamInterface $body)
    {
        return new self($this->psrResponse->withBody($body));
    }

    /**
     * @inheritDoc
     */
    public function withHeader($name, $value)
    {
        return new self($this->psrResponse->withHeader($name, $value));
    }

    /**
     * @inheritDoc
     */
    public function withProtocolVersion($version)
    {
        return new self($this->psrResponse->withProtocolVersion($version));
    }

    /**
     * @inheritDoc
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        return new self($this->psrResponse->withStatus($code));
    }

    /**
     * @inheritDoc
     */
    public function withoutHeader($name)
    {
        return new self($this->psrResponse->withoutHeader($name));
    }

}