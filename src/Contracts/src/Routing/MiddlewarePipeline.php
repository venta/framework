<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface MiddlewarePipeline
 *
 * @package Venta\Contracts\Routing
 */
interface MiddlewarePipeline extends Middleware
{

    /**
     * Return an instance with the specified middleware added to the pipeline.
     *
     * @param Middleware $middleware
     * @return MiddlewarePipeline
     */
    public function withMiddleware(Middleware $middleware): MiddlewarePipeline;

}