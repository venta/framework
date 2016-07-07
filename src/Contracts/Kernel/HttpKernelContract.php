<?php declare(strict_types = 1);

namespace Venta\Framework\Contracts\Kernel;

use Venta\Http\Contract\{
    EmitterContract, RequestContract, ResponseContract
};

/**
 * Interface KernelContract
 *
 * @package Venta\Framework
 */
interface HttpKernelContract extends AbstractKernelContract, EmitterContract
{
    /**
     * Main handle function for application
     *
     * @param  RequestContract $request
     * @return ResponseContract
     */
    public function handle(RequestContract $request): ResponseContract;

}