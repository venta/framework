<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\BootStage;

use Venta\Contracts\Config\Config;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Kernel\KernelBootStage;
use Venta\Contracts\ServiceProvider\ServiceProvider;
use Venta\Framework\ServiceProvider\ServiceProviderDependencyResolver;

/**
 * Class BootServiceProviders
 *
 * @package Venta\Framework\Kernel\BootStage
 */
class BootServiceProviders implements KernelBootStage
{
    /**
     * @inheritDoc
     */
    public function run(Container $container)
    {
        /** @var Config $config */
        $config = $container->get('config');

        /** @var ServiceProviderDependencyResolver $resolver */
        $resolver = new ServiceProviderDependencyResolver();
        $providers = $config->get('providers')->toArray();

        foreach ($resolver->resolve($providers) as $providerClass) {
            /** @var ServiceProvider $provider */
            $provider = new $providerClass($container, $config);
            $provider->boot();
        }

        $config->lock();
    }
}

