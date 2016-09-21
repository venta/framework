<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Venta\Console\Contract\Collector;

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
     * @param Collector $collector
     * @return void
     */
    public function provideCommands(Collector $collector);

}