<?php declare(strict_types = 1);

namespace Venta\Contract;

use Abava\Container\Contract\{
    Caller, Container
};
use Abava\Routing\Contract\Collector as RouteCollector;
use Abava\Routing\Contract\Middleware\Collector as MiddlewareCollector;

/**
 * Interface Application
 *
 * @package Venta\Contract
 */
interface Application extends Container, Caller
{
    /**
     * Returns application version string
     *
     * @return string
     */
    public function version(): string;

    /**
     * Defines, if application is running in CLI
     *
     * @return bool
     */
    public function isCli(): bool;

    /**
     * Returns environment name application is running in
     *
     * @return string
     */
    public function environment(): string;

    /**
     * Defines, if application is in local environment
     *
     * @return bool
     */
    public function isLocalEnvironment(): bool;

    /**
     * Defines, if application is in stage environment
     *
     * @return bool
     */
    public function isStageEnvironment(): bool;

    /**
     * Defines, if application is in live environment
     *
     * @return bool
     */
    public function isLiveEnvironment(): bool;

    /**
     * Defines, if application is in local environment
     *
     * @return bool
     */
    public function isTestEnvironment(): bool;

    /**
     * First function, called in application constructor
     * Is used in order to set up application, before running it.
     */
    public function configure();

    /**
     * Loads extension providers and adds bindings
     *
     * @return  void
     */
    public function bootExtensionProviders();

    /**
     * Function, called in order to terminate application.
     *
     */
    public function terminate();

    /**
     * @param RouteCollector $collector
     * @return void
     */
    public function routes(RouteCollector $collector);

    /**
     * @param MiddlewareCollector $collector
     * @return void
     */
    public function middlewares(MiddlewareCollector $collector);

    /**
     * @param \Symfony\Component\Console\Application $console
     * @return void
     */
    public function commands(\Symfony\Component\Console\Application $console);

}