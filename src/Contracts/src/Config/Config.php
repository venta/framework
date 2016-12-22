<?php declare(strict_types = 1);

namespace Venta\Contracts\Config;

use Countable;
use JsonSerializable;
use Traversable;

/**
 * Interface Config
 *
 * @package Venta\Contracts\Config
 */
interface Config extends Countable, Traversable, JsonSerializable
{
    /**
     * Returns array representation of config.
     *
     * @return array
     */
    public function all(): array;

    /**
     * Returns config value for provided key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null);

    /**
     * Checks if config contains value for provided key.
     *
     * @param $key
     * @return bool
     */
    public function has(string $key): bool;

}