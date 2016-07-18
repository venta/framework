<?php declare(strict_types = 1);

namespace Venta\Contracts\Kernel;

use Abava\Http\Contract\{
    Emitter, Request, Response
};
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface KernelContract
 *
 * @package Venta
 */
interface HttpKernel extends AbstractKernel, Emitter
{
    /**
     * Main handle function for application
     *
     * @param Request|RequestInterface $request
     * @return Response|ResponseInterface
     */
    public function handle(RequestInterface $request): ResponseInterface;

}