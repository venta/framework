<?php declare(strict_types = 1);

namespace Kernel\Module;

use Venta\Framework\Kernel\AbstractKernelModule;
use Venta\Framework\Kernel\Resolver\ServiceProviderDependencyResolver;

/**
 * Class ServiceProviderResolverModule
 *
 * @package Kernel\Module
 */
class ServiceProviderBootSequenceResolvingModule extends AbstractKernelModule
{

    /**
     * @inheritDoc
     */
    public function init()
    {
        $this->container->instance(ServiceProviderDependencyResolver::class, new ServiceProviderDependencyResolver);
    }


}