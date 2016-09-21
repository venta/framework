<?php declare(strict_types = 1);

namespace Venta\Http;

use Venta\Http\Contract\Emitter as EmitterContract;
use Zend\Diactoros\Response\SapiEmitter;

/**
 * Class Emitter
 *
 * @package Venta\Http
 */
class Emitter extends SapiEmitter implements EmitterContract
{
}