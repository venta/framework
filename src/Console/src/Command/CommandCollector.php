<?php declare(strict_types = 1);

namespace Venta\Console\Command;

use Venta\Console\Command;
use Venta\Container\ContainerAwareTrait;
use Venta\Contracts\Console\CommandCollector as CommandCollectorContract;
use Venta\Contracts\Container\ContainerAware;

/**
 * Class Collector
 *
 * @package Venta\Console\Command
 */
class CommandCollector implements CommandCollectorContract, ContainerAware
{
    use ContainerAwareTrait;

    /**
     * Commands holder
     *
     * @var Command[]
     */
    protected $commands = [];

    /**
     * @inheritDoc
     */
    public function addCommand(string $commandClassName)
    {
        if (!is_subclass_of($commandClassName, Command::class)) {
            throw new \InvalidArgumentException(
                sprintf('Provided command "%s" doesn\'t extend Venta\Console\Command class.', $commandClassName)
            );
        }
        $this->commands[] = $commandClassName;
    }

    /**
     * @inheritDoc
     */
    public function getCommands(): array
    {
        return array_map([$this->container, 'get'], $this->commands);
    }

}