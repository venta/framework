<?php declare(strict_types = 1);

namespace Venta\Http;

use Psr\Http\Message\ResponseInterface;
use Venta\Contracts\Http\ResponseEmitter as ResponseEmitterContract;
use Zend\Diactoros\Response\SapiEmitter;
use Zend\Diactoros\Response\SapiEmitterTrait;

/**
 * Class Emitter
 *
 * @package Venta\Http
 */
final class ResponseEmitter extends SapiEmitter implements ResponseEmitterContract
{
    use SapiEmitterTrait;

    /**
     * Emits a response for a PHP SAPI environment.
     *
     * Emits the status line and headers via the header() function, and the
     * body content via the output buffer.
     *
     * @param ResponseInterface $response
     * @param null|int $maxBufferLevel Maximum output buffering level to unwrap.
     */
    public function emit(ResponseInterface $response, $maxBufferLevel = null)
    {
        if (!headers_sent()) {
            $this->emitStatusLine($response);
            $this->emitHeaders($response);
        }
        $this->flush($maxBufferLevel);
        $this->emitBody($response);
    }

    /**
     * Emit the message body.
     *
     * @param ResponseInterface $response
     */
    private function emitBody(ResponseInterface $response)
    {
        echo $response->getBody();
    }
}