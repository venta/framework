<?php declare(strict_types = 1);

namespace Venta\Http\Contract;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Interface RequestFactory
 *
 * @package Venta\Http\Contracts
 */
interface RequestFactory
{
    /**
     * Create a new server request.
     *
     * @param string $method
     * @param UriInterface|string $uri
     *
     * @return ServerRequestInterface
     */
    public function createServerRequest($method, $uri);

    /**
     * Create a new server request from PHP globals.
     *
     * @return ServerRequestInterface
     */
    public function createServerRequestFromGlobals();
}