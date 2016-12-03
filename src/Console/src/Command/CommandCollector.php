<?php declare(strict_types = 1);

namespace Venta\Console\Command;

use Venta\Console\Command;
use Venta\Contracts\Console\CommandCollector as CommandCollectorContract;
use Venta\Contracts\Container\Container;

/**
 * Class Collector
 *
 * @package Venta\Console\Command
 */
class CommandCollector implements CommandCollectorContract
{

    /**
     * Commands holder
     *
     * @var Command[]
     */
    protected $commands = [];

    /**
     * @var Container
     */
    protected $container;

    /**
     * CommandCollector constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

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