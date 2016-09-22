<?php declare(strict_types = 1);

namespace Venta\Contracts\Http;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;

/**
 * Interface Request
 *
 * @package Venta\Contracts\Http
 */
interface Request extends ServerRequestInterface
{
    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withAddedHeader($name, $value);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withAttribute($name, $value);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withBody(StreamInterface $body);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withCookieParams(array $cookies);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withHeader($name, $value);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withMethod($method);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withParsedBody($data);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withProtocolVersion($version);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withQueryParams(array $query);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withRequestTarget($requestTarget);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withUploadedFiles(array $uploadedFiles);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withUri(UriInterface $uri, $preserveHost = false);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withoutAttribute($name);

    /**
     * {@inheritdoc}
     * @return Request
     */
    public function withoutHeader($name);

}