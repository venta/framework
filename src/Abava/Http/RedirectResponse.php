<?php declare(strict_types = 1);

namespace Abava\Http;

use Abava\Http\Contract\Response as ResponseContract;
use Zend\Diactoros\Response\RedirectResponse as BaseRedirectResponse;

/**
 * Class RedirectResponse
 *
 * @package Abava\Http
 */
class RedirectResponse extends BaseRedirectResponse implements ResponseContract
{
    use ResponseTrait;
}