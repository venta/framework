<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Symfony\Component\Console\Application as ConsoleApplication;

/**
 * Interface Commands
 *
 * @package Venta\Contracts\ExtensionProvider
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