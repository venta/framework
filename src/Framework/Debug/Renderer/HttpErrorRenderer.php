<?php declare(strict_types = 1);

namespace Venta\Framework\Debug\Renderer;

use Throwable;
use Venta\Contracts\Debug\ErrorRenderer;
use Venta\Http\ResponseEmitter;
use Zend\Diactoros\Response\HtmlResponse;

/**
 * Class HttpErrorRenderer
 *
 * @package Venta\Framework\Debug\Renderer
 */
final class HttpErrorRenderer implements ErrorRenderer
{
    /**
     * @inheritDoc
     */
    public function render(Throwable $e)
    {
        (new ResponseEmitter)->emit(new HtmlResponse($e->getMessage(), 500));
    }

}