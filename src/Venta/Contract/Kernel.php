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
     * Constants, defining env kernel know about
     */
    const ENV_LOCAL = 'local';
    const ENV_STAGE = 'stage';
    const ENV_LIVE = 'live';
    const ENV_TEST = 'test';

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