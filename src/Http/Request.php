<?php declare(strict_types = 1);

namespace Venta\Http;

use Venta\Http\Contract\Request as RequestContract;
use Zend\Diactoros\ServerRequest as BaseRequest;

/**
 * Class Request
 *
 * @package Venta\Http
 */
class Request extends BaseRequest implements RequestContract
{
}