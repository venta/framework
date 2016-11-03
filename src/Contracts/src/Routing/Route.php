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
     * @return string
     */
    public function getDomain():string;

    /**
     * @return string
     */
    public function getHost(): string;

    /**
     * @return string
     */
    public function getInput(): string;

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
    public function getResponder(): string;

    /**
     * @return string
     */
    public function getScheme(): string;

    /**
     * @return string[]
     */
    public function getVariables(): array;

    /**
     * @param string $domainClass
     * @return Route
     */
    public function withDomain(string $domainClass): Route;

    /**
     * @param string $host
     * @return Route
     */
    public function withHost(string $host): Route;

    /**
     * @param string $inputClass
     * @return Route
     */
    public function withInput(string $inputClass): Route;

    /**
     * @param string $middleware
     * @return Route
     */
    public function withMiddleware(string $middleware): Route;

    /**
     * Set the name.
     *
     * @param string $name
     * @return Route
     */
    public function withName(string $name): Route;

    /**
     * Prefix the path.
     *
     * @param string $prefix
     * @return Route
     */
    public function withPathPrefix(string $prefix): Route;

    /**
     * Set the scheme.
     *
     * @param string $scheme
     * @return Route
     */
    public function withScheme(string $scheme): Route;

    /**
     * Set route parameters.
     *
     * @param array $variables
     * @return Route
     */
    public function withVariables(array $variables): Route;
}