<?php declare(strict_types = 1);

namespace Abava\Console\Contract;

/**
 * Interface Collector
 *
 * @package Abava\Console\Contract
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