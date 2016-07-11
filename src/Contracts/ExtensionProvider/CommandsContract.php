<?php declare(strict_types = 1);

namespace Venta\Framework\Contracts\ExtensionProvider;

use Symfony\Component\Console\Application as ConsoleApplication;

/**
 * Interface CommandsContract
 *
 * @package Venta\Framework\Contracts\ExtensionProvider
 */
interface CommandsContract
{

    /**
     * Add extension console commands
     *
     * @param ConsoleApplication $console
     * @return void
     */
    public function commands(ConsoleApplication $console);

}