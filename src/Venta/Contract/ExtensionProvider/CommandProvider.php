<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Abava\Console\Contract\Collector;

/**
 * Interface CommandProvider
 *
 * @package Venta\Contract\ExtensionProvider
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