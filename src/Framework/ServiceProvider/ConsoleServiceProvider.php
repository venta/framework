<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use Venta\Console\CommandCollection;
use Venta\Contracts\Console\CommandCollection as CommandCollectionContract;
use Venta\Contracts\Container\MutableContainer;
use Venta\Framework\Commands\Shell;
use Venta\ServiceProvider\AbstractServiceProvider;

/**
 * Class ConsoleServiceProvider
 *
 * @package Venta\Framework\ServiceProvider
 */
final class ConsoleServiceProvider extends AbstractServiceProvider
{

    /**
     * @inheritDoc
     */
    public function bind(MutableContainer $container)
    {
        $container->bind(CommandCollectionContract::class, CommandCollection::class);

        $this->provideCommands(
            Shell::class
        );
    }
}