<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Http\CookieJar as CookieJarContract;
use Venta\Contracts\Http\ResponseEmitter as ResponseEmitterContract;
use Venta\Contracts\Http\ResponseFactory as ResponseFactoryContract;
use Venta\Http\CookieJar;
use Venta\Http\ResponseEmitter;
use Venta\Http\ResponseFactory;
use Venta\ServiceProvider\AbstractServiceProvider;
use Zend\Diactoros\ServerRequestFactory;

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
        $this->container->bindClass(CookieJarContract::class, CookieJar::class, true);

        $this->container->bindFactory(
            ServerRequestInterface::class,
            [ServerRequestFactory::class, 'fromGlobals'],
            true
        );
    }
}
