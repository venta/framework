<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\Resolver;

use LogicException;

/**
 * Class ServiceProviderDependencyResolver
 *
 * @package Venta\Framework\Kernel\Resolver
 */
class ServiceProviderDependencyResolver
{
    /**
     * Array of service provider classes currently being resolved.
     *
     * @var array
     */
    protected $resolving = [];

    /**
     * Array of service provider classes ordered in respecting their dependencies.
     *
     * @var array
     */
    protected $sequence = [];

    /**
     * @param array $providers
     * @return array
     */
    public function resolve(array $providers): array
    {
        foreach ($providers as $provider) {
            $this->detectCircularDependency($provider);
            $this->resolving[$provider] = $provider;
            $dependencies = $provider::getDependencies();
            if (!empty($dependencies)) {
                $this->resolve($dependencies);
            }

            if (!in_array($provider, $this->sequence)) {
                $this->sequence[] = $provider;
            }

            unset($this->resolving[$provider]);
        }

        return $this->sequence;
    }

    /**
     * Detects circular dependency between two or more service providers.
     *
     * @param string $provider
     * @throws LogicException
     */
    private function detectCircularDependency(string $provider)
    {
        if (isset($this->resolving[$provider])) {
            $this->resolving[] = $provider;
            throw new LogicException(sprintf(
                'Circular reference detected for service provider "%s", path: "%s".',
                $provider,
                implode(' -> ', $this->resolving)
            ));
        }
    }
}