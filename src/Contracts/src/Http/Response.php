<?php declare(strict_types = 1);

namespace Venta\Contracts\Http;

use Psr\Http\Message\ResponseInterface;

/**
 * Interface Response
 *
 * @package Venta\Contracts\Http
 */
interface Response extends ResponseInterface
{
    /**
     * Writes provided string to response body stream
     *
     * @param string $body
     * @return Response
     */
    public function append(string $body): Response;

    /**
     * Get body content as plain text
     *
     * @return string
     */
    public function getContent(): string;

}