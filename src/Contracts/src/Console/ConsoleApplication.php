<?php declare(strict_types = 1);

namespace Venta\Contracts\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface ConsoleApplication
 *
 * @package Venta\Contracts\Console
 */
interface ConsoleApplication
{

    /**
     * Runs Console (cli) Application
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    public function run(InputInterface $input = null, OutputInterface $output = null);

}