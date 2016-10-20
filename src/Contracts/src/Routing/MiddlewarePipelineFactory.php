<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface MiddlewarePipelineFactory
 *
 * @package Contracts\src\Routing
 */
interface MiddlewarePipelineFactory
{

    /**
     * Creates middleware pipeline from list of middleware class names.
     *
     * @param string[] $middlewares
     * @return MiddlewarePipeline
     */
    public function create(array $middlewares): MiddlewarePipeline;

}