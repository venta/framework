<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface UrlBuilder
 *
 * @package Venta\Contracts\Routing
 */
interface UrlBuilder
{

    /**
     * Build url using provided parameters on placeholders.
     *
     * @param array $parameters
     * @return string
     */
    public function url(array $parameters = []): string;

}