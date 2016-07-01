<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Framework\Contracts\ApplicationContract;
use Venta\Framework\Contracts\Kernel\ConsoleKernelContract;

/**
 * Class ConsoleKernel
 *
 * @package Venta\Framework
 */
class ConsoleKernel implements ConsoleKernelContract
{
    /**
     * Application instance holder
     *
     * @var ApplicationContract
     */
    protected $application;

    /**
     * {@inheritdoc}
     */
    public function __construct(ApplicationContract $application)
    {
        $this->application = $application;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(InputInterface $input = null, OutputInterface $output = null): int
    {
        $console = new Application('Venta Console Application', $this->application->version());
        $commandsCollector = function () use ($console) { $this->callExtensionProvidersMethod('commands', $console); };
        $commandsCollector = $commandsCollector->bindTo($this->application, $this->application);

        $commandsCollector();

        return $console->run($input, $output);
    }
}