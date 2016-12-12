<?php declare(strict_types = 1);

namespace Venta\Contracts\Config;

use Countable;
use IteratorAggregate;
use JsonSerializable;

/**
 * Interface Config
 *
 * @package Venta\Contracts\Config
 */
interface Config extends Countable, IteratorAggregate, JsonSerializable
{
    /**
     * Returns config value for provided key.
     *
     * @param string $key
     * @return mixed
     */
    public function __get(string $key);

    /**
     * Checks if config contains value for provided key.
     *
     * @param $key
     * @return bool
     */
    public function __isset(string $key): bool;

    /**
     * Returns array representation of config.
     *
     * @return array
     */
    public function toArray(): array;

}