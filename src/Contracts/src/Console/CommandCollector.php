<?php declare(strict_types = 1);

namespace Venta\Contracts\Console;

use Venta\Console\Command;

/**
 * Interface CommandCollector
 *
 * @package Venta\Contracts\Console
 */
interface CommandCollector
{

    /**
     * Add command to collector
     *
     * @param string $commandClassName
     * @return void
     */
    public function addCommand(string $commandClassName);

    /**
     * Get collected commands
     *
     * @return Command[]
     */
    public function getCommands(): array;

}