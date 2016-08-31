<?php declare(strict_types = 1);

namespace Venta\Commands;

use Abava\Console\Command;
use Psy\Shell as BaseShell;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Shell
 *
 * @package Venta\Commands
 * @codeCoverageIgnore
 */
class Shell extends Command
{
    /**
     * @inheritDoc
     */
    public function description(): string
    {
        return 'Interactive shell';
    }

    /**
     * @inheritDoc
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $shell = new BaseShell;
        $shell->setIncludes($input->getArgument('includes'));
        $shell->run();
    }

    /**
     * @inheritDoc
     */
    public function signature(): string
    {
        return 'shell {includes[]=:Array of files to include in shell}';
    }
}