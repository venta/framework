<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use Venta\Console\CommandCollection;
use Venta\Contracts\Console\CommandCollection as CommandCollectionContract;
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
    public function boot()
    {
        $this->container()->bindClass(CommandCollectionContract::class, CommandCollection::class, true);

        $this->provideCommands(
            Shell::class
        );
    }
}