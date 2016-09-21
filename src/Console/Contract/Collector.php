<?php declare(strict_types = 1);

namespace Venta\Console\Contract;

/**
 * Interface Collector
 *
 * @package Venta\Console\Contracts
 */
interface Collector
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