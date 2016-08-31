<?php declare(strict_types = 1);

namespace Abava\Console\Command;

use Abava\Console\Contract\Collector as CollectorContract;
use Abava\Console\Contract\Command;
use Abava\Container\Contract\Container;

/**
 * Class Collector
 *
 * @package Abava\Console\Command
 */
class Collector implements CollectorContract
{

    /**
     * @var Container
     */
    protected $container;

    /**
     * Commands holder
     *
     * @var Command[]
     */
    protected $commands = [];

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function addCommand(string $commandClassName)
    {
        if (!is_subclass_of($commandClassName, \Abava\Console\Command::class)) {
            throw new \InvalidArgumentException(
                sprintf('Provided command "%s" doesn\'t extend Abava\Console\Command class.', $commandClassName)
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