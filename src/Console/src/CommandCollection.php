<?php declare(strict_types = 1);

namespace Venta\Console;

use ArrayIterator;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;
use Venta\Contracts\Console\CommandCollection as CommandCollectionContract;

/**
 * Class CommandCollection
 *
 * @package Venta\Console
 */
final class CommandCollection implements CommandCollectionContract
{
    /**
     * @var string[]
     */
    private $commands = [];

    /**
     * @inheritDoc
     */
    public function add(string $commandClass)
    {
        if (!is_subclass_of($commandClass, Command::class)) {
            throw new InvalidArgumentException(
                sprintf('Provided command "%s" is not subclass of "%s" class.', $commandClass, Command::class)
            );
        }
        $this->commands[] = $commandClass;
    }

    /**
     * @inheritDoc
     */
    public function all(): array
    {
        return array_unique($this->commands);
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        return new ArrayIterator($this->all());
    }
}