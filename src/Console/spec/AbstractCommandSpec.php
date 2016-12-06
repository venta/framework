<?php

namespace spec\Venta\Console;

use PhpSpec\ObjectBehavior;
use stub\Venta\Console\StubCommand;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Console\AbstractCommand;

class AbstractCommandSpec extends ObjectBehavior
{
    function let()
    {
        $this->beAnInstanceOf(StubCommand::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(AbstractCommand::class);
    }

    function it_runs(OutputInterface $output)
    {
        $this->run(new ArrayInput(['argument' => 4, '--option' => 2]), $output)->shouldBe(42);
        $output->write(42)->shouldHaveBeenCalled();
    }
}
