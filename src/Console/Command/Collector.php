<?php declare(strict_types = 1);

namespace Venta\Console\Command;

use Venta\Console\Contract\Collector as CollectorContract;
use Venta\Console\Contract\Command;
use Venta\Container\Contract\Container;

/**
 * Class Collector
 *
 * @package Venta\Console\Command
 */
class Collector implements CollectorContract
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

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function addCommand(string $commandClassName)
    {
        if (!is_subclass_of($commandClassName, \Venta\Console\Command::class)) {
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
        return array_map(function ($command) {
            return $this->container->get($command);
        }, $this->commands);
    }

}