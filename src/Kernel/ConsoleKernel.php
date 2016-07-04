<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel;

use Symfony\Component\Console\Application as ConsoleApplication;
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
        // creating new Symfony Console Application
        $console = new ConsoleApplication('Venta Console Application', $this->application->version());
        // loading extension providers and calling ->bindings()
        $this->application->bootExtensionProviders();
        // collecting commands from extension providers
        // todo Make a workaround for collectors
        (function () use ($console) { $this->callExtensionProvidersMethod('commands', $console); })->bindTo($this->application, $this->application)();
        // running console application
        return $console->run($input, $output);
    }
}