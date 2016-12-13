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
     * Returns route path with placeholders substituted with variables.
     *
     * @param array $variables
     * @return string
     */
    public function compilePath(array $variables = []): string;

    /**
     * @return string
     */
    public function domain(): string;

    /**
     * @return string
     */
    public function host(): string;

    /**
     * @return string
     */
    public function input(): string;

    /**
     * @return string[]
     */
    public function methods(): array;

    /**
     * @return string[]
     */
    public function middlewares(): array;

    /**
     * @return string
     */
    public function name(): string;

    /**
     * @return string
     */
    public function path(): string;

    /**
     * @return string
     */
    public function responder(): string;

    /**
     * @return string
     */
    public function scheme(): string;

    /**
     * @return string[]
     */
    public function variables(): array;

    /**
     * Set the HTTPS scheme.
     *
     * @return Route
     */
    public function secure(): Route;

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
    public function withPath(string $prefix): Route;

    /**
     * Set route parameters.
     *
     * @param array $variables
     * @return Route
     */
    public function withVariables(array $variables): Route;
}