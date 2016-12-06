<?php declare(strict_types = 1);

namespace Venta\Contracts\Console;

use IteratorAggregate;

/**
 * Interface CommandCollection
 *
 * @package Venta\Contracts\Console
 */
interface CommandCollection extends IteratorAggregate
{

    /**
     * Adds command to collection.
     *
     * @param string $commandClass
     * @return void
     */
    public function addCommand(string $commandClass);

    /**
     * Returns all commands.
     *
     * @return string[]
     */
    public function getCommands(): array;

}