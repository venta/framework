<?php

namespace stub\Venta\Console;

use Venta\Console\AbstractCommand;

class StubCommand extends AbstractCommand
{
    protected $signature = 'stub:command {argument} {--option}';

    /**
     * @inheritDoc
     */
    protected function handle()
    {
        $this->output->write($this->input->getArgument('argument') . $this->input->getOption('option'));

        return 42;
    }
}