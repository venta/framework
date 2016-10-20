<?php declare(strict_types = 1);

namespace Venta\Contracts\Routing;

/**
 * Interface Route
 *
 * @package Venta\Contracts\Routing
 */
interface Route
{

    /**
     * @return string|callable
     */
    public function getHandler();

    /**
     * @return string
     */
    public function getHost(): string;

    /**
     * @return string[]
     */
    public function getMethods(): array;

    /**
     * @return string[]
     */
    public function getMiddlewares(): array;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return string
     */
    public function getPath(): string;

    /**
     * @return string
     */
    public function getScheme(): string;

    /**
     * @return string[]
     */
    public function getVariables(): array;

}