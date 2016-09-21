<?php declare(strict_types = 1);

namespace Venta\Console\Contract;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface Command
 *
 * @package Venta\Console
 */
interface Command
{
    /**
     * Returns command description text
     *
     * @return string
     */
    public function description(): string;

    /**
     * Main command function, which is executed on command run
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|int
     */
    public function handle(InputInterface $input, OutputInterface $output);

    /**
     * Should return string with command signature
     *
     * @return string
     */
    public function signature(): string;

}