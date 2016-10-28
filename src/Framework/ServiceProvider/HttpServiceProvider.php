<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use Venta\Contracts\Http\Request;
use Venta\Contracts\Http\ResponseEmitter as ResponseEmitterContract;
use Venta\Contracts\Http\ResponseFactory as ResponseFactoryContract;
use Venta\Http\Factory\RequestFactory;
use Venta\Http\Factory\ResponseFactory;
use Venta\Http\ResponseEmitter;
use Venta\ServiceProvider\AbstractServiceProvider;

/**
 * Class HttpServiceProvider
 *
 * @package Venta\Framework\ServiceProvider
 */
class HttpServiceProvider extends AbstractServiceProvider
{

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->container->set(ResponseFactoryContract::class, ResponseFactory::class, true);
        $this->container->set(ResponseEmitterContract::class, ResponseEmitter::class, true);

        // todo: bind to factory interface (if possible)
        $this->container->factory(
            Request::class,
            [RequestFactory::class, 'createServerRequestFromGlobals'],
            true
        );
    }
}
