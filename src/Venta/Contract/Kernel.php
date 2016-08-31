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
     * Constants, defining environment kernel is aware of
     */
    const ENV_LOCAL = 'local';
    const ENV_STAGE = 'stage';
    const ENV_LIVE = 'live';
    const ENV_TEST = 'test';

    /**
     * Bootstraps kernel and returns container instance
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
    public function getEnvironment(): string;

    /**
     * If running in cli
     *
     * @return bool
     */
    public function isCli(): bool;

}