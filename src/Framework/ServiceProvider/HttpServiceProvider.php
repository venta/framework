<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Container\MutableContainer;
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
    public function bind(MutableContainer $container)
    {
        $container->bind(ResponseFactoryContract::class, ResponseFactory::class);
        $container->bind(ResponseEmitterContract::class, ResponseEmitter::class);
        $container->bind(CookieJarContract::class, CookieJar::class);

        $container->factory(ServerRequestInterface::class, [ServerRequestFactory::class, 'fromGlobals'], true);
    }
}
