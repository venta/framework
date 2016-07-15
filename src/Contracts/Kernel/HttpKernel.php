<?php declare(strict_types = 1);

namespace Venta\Contracts\Kernel;

use Abava\Http\Contract\{
    EmitterContract, RequestContract, ResponseContract
};

/**
 * Interface KernelContract
 *
 * @package Venta
 */
interface HttpKernel extends AbstractKernel, EmitterContract
{
    /**
     * Main handle function for application
     *
     * @param  RequestContract $request
     * @return ResponseContract
     */
    public function handle(RequestContract $request): ResponseContract;

}