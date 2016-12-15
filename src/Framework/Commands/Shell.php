<?php declare(strict_types = 1);

namespace Venta\Framework\Commands;

use Psy\Shell as BaseShell;
use Venta\Console\AbstractCommand;

/**
 * Class Shell
 *
 * @package Venta\Commands
 * @codeCoverageIgnore
 */
class Shell extends AbstractCommand
{
    protected $description = 'Interactive shell';

    protected $signature = 'shell {includes[]=:Array of files to include in shell}';

    /**
     * @inheritDoc
     */
    public function handle()
    {
        $shell = new BaseShell;
        $shell->setIncludes($this->input()->getArgument('includes'));
        $shell->run();
    }

}