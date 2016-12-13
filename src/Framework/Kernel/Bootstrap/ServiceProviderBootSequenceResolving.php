<?php declare(strict_types = 1);

namespace Kernel\Bootstrap;

use Venta\Framework\Kernel\AbstractKernelBootstrap;
use Venta\Framework\Kernel\Resolver\ServiceProviderDependencyResolver;

/**
 * Class ServiceProviderBootSequenceResolving
 *
 * @package Kernel\Bootstrap
 */
class ServiceProviderBootSequenceResolving extends AbstractKernelBootstrap
{

    /**
     * @inheritDoc
     */
    public function __invoke()
    {
        $this->container->bindInstance(ServiceProviderDependencyResolver::class, new ServiceProviderDependencyResolver);
    }

}