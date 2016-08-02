<?php declare(strict_types = 1);

namespace Abava\Http;

use Abava\Http\Contract\Response as ResponseContract;
use Zend\Diactoros\Response\JsonResponse as BaseJsonResponse;

/**
 * Class JsonResponse
 *
 * @package Abava\Http
 */
class JsonResponse extends BaseJsonResponse implements ResponseContract
{
    use ResponseTrait;
}