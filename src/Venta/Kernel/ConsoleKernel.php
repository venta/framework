<?php declare(strict_types = 1);

namespace Venta\Kernel;

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Contracts\Application;
use Venta\Contracts\Kernel\ConsoleKernel as ConsoleKernelContact;

/**
 * Class ConsoleKernel
 *
 * @package Venta
 */
class ConsoleKernel extends ConsoleApplication implements ConsoleKernelContact
{
    /**
     * Application instance holder
     *
     * @var Application
     */
    protected $application;

    /**
     * {@inheritdoc}
     */
    public function setApplication(Application $application)
    {
        $this->application = $application;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(InputInterface $input, OutputInterface $output): int
    {
        $this->application->bind('console', $this);
        // loading extension providers and calling ->bindings()
        $this->application->bootExtensionProviders();
        // collecting commands from extension providers
        // todo Make a workaround for collectors
        (function () { $this->callExtensionProvidersMethod('commands', $this->get('console')); })->bindTo($this->application, $this->application)();
        // running console application
        $status = $this->run($input, $output);
        $this->application->bind('status', $status);
        return $status;
    }

    /**
     * {@inheritdoc}
     */
    public function terminate()
    {
        $this->application->terminate();
    }

    /**
     * Making run method final
     *
     * {@inheritdoc}
     */
    final public function run(InputInterface $input = null, OutputInterface $output = null)
    {
        return parent::run($input, $output);
    }

}