<?php declare(strict_types = 1);

namespace Venta\Debug\Renderer;

use Throwable;
use Venta\Contracts\Debug\ErrorRenderer;
use Venta\Contracts\Http\ResponseEmitter;
use Venta\Contracts\Http\ResponseFactory;

/**
 * Class HttpErrorRenderer
 *
 * @package Venta\Debug\Renderer
 */
final class HttpErrorRenderer implements ErrorRenderer
{
    /**
     * @var ResponseEmitter
     */
    private $responseEmitter;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    /**
     * HttpErrorRenderer constructor.
     *
     * @param ResponseFactory $responseFactory
     * @param ResponseEmitter $responseEmitter
     */
    public function __construct(ResponseFactory $responseFactory, ResponseEmitter $responseEmitter)
    {
        $this->responseFactory = $responseFactory;
        $this->responseEmitter = $responseEmitter;
    }

    /**
     * @inheritDoc
     */
    public function render(Throwable $e)
    {
        //todo: implement properly formatted response.
        $response = $this->responseFactory->createHtmlResponse($e->getMessage());
        $this->responseEmitter->emit($response);
    }

}