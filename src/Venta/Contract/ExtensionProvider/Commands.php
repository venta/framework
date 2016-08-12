<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Symfony\Component\Console\Application as ConsoleApplication;

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
     * @param ConsoleApplication $console
     * @return void
     */
    public function commands(ConsoleApplication $console);

}