<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface UrlGenerator
 *
 * @package Venta\Contracts\Routing
 */
interface UrlGenerator
{

    /**
     * Generate url to named route
     *
     * @param string $name
     * @param array $parameters
     * @return string
     */
    public function url(string $name, array $parameters = []): string;

}