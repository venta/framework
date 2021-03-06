<?php declare(strict_types = 1);

namespace Venta\Container;

use ReflectionFunctionAbstract;
use ReflectionParameter;
use Venta\Container\Exception\ArgumentResolverException;
use Venta\Contracts\Container\ArgumentResolver as ArgumentResolverContract;
use Venta\Contracts\Container\Container as ContainerContract;

/**
 * Class ArgumentResolver.
 *
 * @package Venta\Container
 */
final class ArgumentResolver implements ArgumentResolverContract
{
    /**
     * @var ContainerContract
     */
    private $container;

    /**
     * ArgumentResolver constructor.
     *
     * @param ContainerContract $container
     */
    public function __construct(ContainerContract $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function resolve(ReflectionFunctionAbstract $function, array $arguments = []): array
    {
        $parameters = $function->getParameters();

        // Use passed arguments in place of reflected parameters.
        $provided = array_intersect_key($arguments, $parameters);

        // Remaining parameters will be resolved by container.
        $remaining = array_diff_key($parameters, $arguments);
        $resolved = array_map(
            function (ReflectionParameter $parameter) use ($function) {

                // Recursively resolve function arguments.
                $class = $parameter->getClass();
                if ($class !== null && $this->container->has($class->getName())) {
                    return $this->container->get($class->getName());
                }

                // Use argument default value if possible.
                if ($parameter->isDefaultValueAvailable()) {
                    return $parameter->getDefaultValue();
                }

                // The argument can't be resolved by this resolver.
                throw new ArgumentResolverException($parameter, $function);

            },
            $remaining
        );

        $arguments = $provided + $resolved;

        // Sort combined result array by parameter indexes.
        ksort($arguments);

        return $arguments;
    }
}