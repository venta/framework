<?php declare(strict_types = 1);

namespace Venta\Framework\Contracts\Kernel;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface ConsoleKernelContract
 *
 * @package Venta\Framework
 */
interface ConsoleKernelContract extends AbstractKernelContract
{
    /**
     * Main application handle function
     * Returns status to exit with
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    public function handle(InputInterface $input = null, OutputInterface $output = null): int;

    /**
     * Called in order to terminate application
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @param  int             $status
     */
//    public function terminate(InputInterface $input, OutputInterface $output, int $status);
}