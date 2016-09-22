<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Contracts\Console\CommandCollector;

/**
 * Interface CommandProvider
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface CommandProvider
{

    /**
     * Add extension console commands
     *
     * @param CommandCollector $collector
     * @return void
     */
    public function provideCommands(CommandCollector $collector);

}