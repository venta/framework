<?php declare(strict_types = 1);

namespace Venta\Http;

use Venta\Http\Contract\Response as ResponseContract;
use Zend\Diactoros\Response\RedirectResponse as BaseRedirectResponse;

/**
 * Class RedirectResponse
 *
 * @package Venta\Http
 */
class RedirectResponse extends BaseRedirectResponse implements ResponseContract
{
    use ResponseTrait;
}