<?php declare(strict_types = 1);

namespace Venta\Framework\Http;

use Venta\Framework\Contracts\Kernel\Http\EmitterContract;
use Zend\Diactoros\Response\SapiEmitter;

/**
 * Class Emitter
 *
 * @package Venta\Framework\Http
 */
class Emitter extends SapiEmitter implements EmitterContract{}