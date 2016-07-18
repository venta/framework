<?php declare(strict_types=1);

namespace Abava\Console\Contract;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface Command
 *
 * @package Abava\Console
 */
interface Command
{
    /**
     * Should return string with command signature
     *
     * @return string
     */
    public function signature(): string;

    /**
     * Returns command description text
     *
     * @return string
     */
    public function description(): string;

    /**
     * Main command function, which is executed on command run
     *
     * @param  \Symfony\Component\Console\Input\InputInterface $input
     * @param  \Symfony\Component\Console\Output\OutputInterface $output
     * @return null|int
     */
    public function handle(InputInterface $input, OutputInterface $output);

}