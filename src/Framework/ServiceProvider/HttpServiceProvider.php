<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Http\CookieJar as CookieJarContract;
use Venta\Contracts\Http\Request;
use Venta\Contracts\Http\RequestFactory as RequestFactoryContract;
use Venta\Contracts\Http\ResponseEmitter as ResponseEmitterContract;
use Venta\Contracts\Http\ResponseFactory as ResponseFactoryContract;
use Venta\Http\CookieJar;
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
        $this->container->bindClass(ResponseFactoryContract::class, ResponseFactory::class, true);
        $this->container->bindClass(ResponseEmitterContract::class, ResponseEmitter::class, true);
        $this->container->bindClass(RequestFactoryContract::class, RequestFactory::class, true);

        $this->container->bindFactory(
            Request::class,
            [RequestFactoryContract::class, 'createServerRequestFromGlobals'],
            true
        );

        $this->container->bindClass(ServerRequestInterface::class, Request::class);
        $this->container->bindClass(CookieJarContract::class, CookieJar::class, true);
    }
}
