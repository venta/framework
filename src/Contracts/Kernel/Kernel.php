<?php declare(strict_types = 1);

namespace Venta\Contracts\Kernel;

use Venta\Contracts\Container\Container;

/**
 * Interface Kernel
 *
 * @package Venta\Contracts
 */
interface Kernel
{
    /**
     * Constants, defining environment kernel is aware of
     */
    const ENV_LOCAL = 'local';
    const ENV_STAGE = 'stage';
    const ENV_LIVE = 'live';
    const ENV_TEST = 'test';

    /**
     * Bootstraps kernel and returns container instance
     *
     * @return \Venta\Contracts\Container\Container
     */
    public function boot(): Container;

    /**
     * Returns current running environment
     *
     * @return string
     */
    public function getEnvironment(): string;

    /**
     * Returns kernel version
     *
     * @return string
     */
    public function getVersion(): string;

    /**
     * If running in cli
     *
     * @return bool
     */
    public function isCli(): bool;

}