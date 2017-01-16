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
final class HttpServiceProvider extends AbstractServiceProvider
{

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->container()->bind(ResponseFactoryContract::class, ResponseFactory::class);
        $this->container()->bind(ResponseEmitterContract::class, ResponseEmitter::class);
        $this->container()->bind(CookieJarContract::class, CookieJar::class);

        $this->container()->factory(
            ServerRequestInterface::class,
            [ServerRequestFactory::class, 'fromGlobals'],
            true
        );
    }
}
