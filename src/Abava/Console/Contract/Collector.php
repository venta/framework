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
     * @param Command $command
     * @return void
     */
    public function addCommand(Command $command);

    /**
     * Get collected commands
     *
     * @return Command[]
     */
    public function getCommands(): array;

}