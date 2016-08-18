<?php declare(strict_types = 1);

namespace Venta\Contract;

use Abava\Container\Contract\Container;

/**
 * Interface Kernel
 *
 * @package Venta\Contract
 */
interface Kernel
{

    /**
     * Bootstraps:
     *  - load extension providers
     *  - register container bindings
     *  - collect routes, middlewares, commands
     *
     * @return Container
     */
    public function boot(): Container;

    /**
     * Returns kernel version
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * Returns current running environment
     *
     * @return string
     */
    public function environment(): string;

    /**
     * If running in cli
     *
     * @return bool
     */
    public function isCli(): bool;

}