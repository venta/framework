<?php declare(strict_types = 1);

namespace Abava\Console\Command;

use Abava\Console\Contract\Command;

/**
 * Class Collector
 *
 * @package Abava\Console\Command
 */
class Collector implements \Abava\Console\Contract\Collector
{

    /**
     * Commands holder
     *
     * @var Command[]
     */
    protected $commands = [];

    /**
     * @inheritDoc
     */
    public function addCommand(Command $command)
    {
        $this->commands[] = $command;
    }

    /**
     * @inheritDoc
     */
    public function getCommands(): array
    {
        return $this->commands;
    }

}