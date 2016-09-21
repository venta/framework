<?php declare(strict_types = 1);

namespace Venta\Http;

use Venta\Http\Contract\Response as ResponseContract;
use Zend\Diactoros\Response\JsonResponse as BaseJsonResponse;

/**
 * Class JsonResponse
 *
 * @package Venta\Http
 */
class JsonResponse extends BaseJsonResponse implements ResponseContract
{
    use ResponseTrait;
}