<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Abava\Console\Contract\Collector;

/**
 * Interface Commands
 *
 * @package Venta\Contract\ExtensionProvider
 */
interface Commands
{

    /**
     * Add extension console commands
     *
     * @param Collector $collector
     * @return void
     */
    public function commands(Collector $collector);

}