<?php declare(strict_types = 1);

namespace Venta\Kernel;

use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Contract\Application;
use Venta\Contract\Kernel\ConsoleKernel as ConsoleKernelContact;

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
        // Register instances
        $this->application->singleton('console', $this);
        $this->application->singleton('input', $input);
        $this->application->singleton(InputInterface::class, $input);
        $this->application->singleton('output', $output);
        $this->application->singleton(OutputInterface::class, $output);

        // loading extension providers and calling ->bindings()
        $this->application->bootExtensionProviders();

        // collecting commands from extension providers
        $this->application->commands($this);

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

    /**
     * @inheritDoc
     */
    public function renderException(\Exception $e, OutputInterface $output)
    {
        if ($this->application->has('error_handler')) {
            /** @var \Whoops\RunInterface $run */
            $run = $this->application->get('error_handler');
            // from now on ConsoleApplication will render exception
            $run->allowQuit(false);
            $run->writeToOutput(false);
            // Ignore the return string, parent call will render exception
            $run->handleException($e);
        }
        parent::renderException($e, $output);
    }


}