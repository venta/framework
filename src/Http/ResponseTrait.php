<?php declare(strict_types = 1);

namespace Venta\Http;

use Venta\Http\Contract\Response as ResponseContract;
use Psr\Http\Message\StreamInterface;

/**
 * Class ResponseTrait
 *
 * @package Venta\Http
 * @method StreamInterface getBody()
 */
trait ResponseTrait
{
    /**
     * Writes provided string to response body stream
     *
     * @param string $body
     * @return ResponseContract
     */
    public function append(string $body): ResponseContract
    {
        $this->getBody()->write($body);

        return $this;
    }

    /**
     * Returns body as a string
     *
     * @return string
     */
    public function getContent()
    {
        return (string)$this->getBody();
    }
}