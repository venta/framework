<?php declare(strict_types = 1);

namespace Venta\Http;

use Venta\Contracts\Http\ResponseEmitter as ResponseEmitterContract;
use Zend\Diactoros\Response\SapiEmitter;

/**
 * Class Emitter
 *
 * @package Venta\Http
 */
class ResponseEmitter extends SapiEmitter implements ResponseEmitterContract
{
}