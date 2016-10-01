<?php declare(strict_types = 1);

namespace Venta\Contracts\ServiceProvider;

/**
 * Interface ServiceProvider.
 *
 * @package Venta\Contracts\ServiceProvider
 */
interface ServiceProvider
{
    /**
     * Returns a list of service provider names the package depends on.
     *
     * @return array
     */
    public static function getDependencies(): array;

    /**
     * Returns the service provider name.
     *
     * @return string
     */
    public static function getName(): string;

    /**
     * Boots the service provider.
     *
     * @return mixed
     */
    public function boot();

}