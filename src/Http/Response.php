<?php declare(strict_types=1);

namespace Venta\Framework\Http;

/**
 * Class Response
 *
 * @package Venta\Framework\Http
 */
class Response extends \Zend\Diactoros\Response
{

    /**
     * Writes provided string to response body
     *
     * @param string $body
     * @return $this
     */
    public function append(string $body)
    {
        $this->getBody()->write($body);
        return $this;
    }

}