<?php declare(strict_types = 1);

namespace Abava\Http;

use Abava\Http\Contract\Response as ResponseContract;
use Zend\Diactoros\Response as BaseResponse;

/**
 * Class Response
 *
 * @package Abava\Http
 */
class Response extends BaseResponse implements ResponseContract
{
    use ResponseTrait;
}