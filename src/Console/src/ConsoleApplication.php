<?php declare(strict_types = 1);

namespace Venta\Console;

use Error;
use ErrorException;
use Symfony\Component\Console\Application as SymfonyConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Throwable;
use Venta\Contracts\Console\CommandCollection;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Kernel\Kernel;

/**
 * Class ConsoleApplication
 *
 * @package Venta\Console
 */
final class ConsoleApplication
{
    /**
     * @var SymfonyConsoleApplication
     */
    private $console;

    /**
     * @var Container
     */
    private $container;

    /**
     * ConsoleApplication constructor.
     *
     * @param Kernel $kernel
     */
    public function __construct(Kernel $kernel)
    {
        $this->container = $kernel->boot();
        $this->initConsole('Venta Console', $kernel->getVersion());
    }

    /**
     * Runs Console Application.
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    public function run(InputInterface $input, OutputInterface $output): int
    {
        try {
            return $this->console->run($input, $output);
        } catch (Throwable $e) {

            // todo: call error reporters

            if ($e instanceof Error) {
                $e = new ErrorException(
                    $e->getMessage(), 0, $e->getCode(), $e->getFile(), $e->getLine(), $e->getPrevious()
                );
            }

            $this->console->renderException($e, $output);

            return 1;
        }
    }

    /**
     * Initiates Symfony Console Application.
     *
     * @param string $name
     * @param string $version
     */
    private function initConsole(string $name, string $version)
    {
        $this->console = $this->container->get(SymfonyConsoleApplication::class);
        $this->console->setName($name);
        $this->console->setVersion($version);
        $this->console->setCatchExceptions(false);
        $this->console->setAutoExit(false);

        /** @var CommandCollection $commands */
        $commands = $this->container->get(CommandCollection::class);
        foreach ($commands as $command) {
            $this->console->add($this->resolveCommand($command));
        }
    }

    /**
     * Resolves command object from class name.
     *
     * @param string $commandClass
     * @return mixed
     */
    private function resolveCommand(string $commandClass)
    {
        return $this->container->get($commandClass);
    }

}