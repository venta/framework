<?php declare(strict_types = 1);

namespace Venta\Contracts\Kernel;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Contracts\Application;

/**
 * Interface ConsoleKernel
 *
 * @package Venta
 */
interface ConsoleKernel extends AbstractKernel
{
    /**
     * Main application handle function
     * Returns status to exit with
     *
     * @param  InputInterface  $input
     * @param  OutputInterface $output
     * @return int
     */
    public function handle(InputInterface $input, OutputInterface $output): int;

    /**
     * Set Application instance
     *
     * @param Application $application
     * @return void
     */
    public function setApplication(Application $application);

}