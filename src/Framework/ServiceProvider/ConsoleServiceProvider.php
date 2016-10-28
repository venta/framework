<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Console\Command\CommandCollector;
use Venta\Contracts\Console\CommandCollector as CommandCollectorContract;
use Venta\Framework\Commands\Shell;
use Venta\ServiceProvider\AbstractServiceProvider;

/**
 * Class ConsoleServiceProvider
 *
 * @package Venta\Framework\ServiceProvider
 */
class ConsoleServiceProvider extends AbstractServiceProvider
{

    /**
     * @inheritDoc
     */
    public function boot()
    {
        // todo: refactor along with console package.
        $this->container->factory(InputInterface::class, function () {
            return new ArgvInput;
        }, true);

        $this->container->factory(OutputInterface::class, function () {
            return new ConsoleOutput;
        }, true);

        $this->container->set(CommandCollectorContract::class, CommandCollector::class, true);

        $this->provideCommands(
            Shell::class
        );

    }
}