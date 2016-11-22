<?php declare(strict_types = 1);

namespace Venta\Contracts\Http;

/**
 * Interface ResponseFactoryAware
 *
 * @package Venta\Contracts\Http
 */
interface ResponseFactoryAware
{

    /**
     * Response factory setter.
     *
     * @param ResponseFactory $factory
     * @return void
     */
    public function setResponseFactory(ResponseFactory $factory);

}