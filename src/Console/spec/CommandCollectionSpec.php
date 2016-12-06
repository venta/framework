<?php

namespace spec\Venta\Console;

use InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use stdClass;
use stub\Venta\Console\StubCommand;
use Venta\Contracts\Console\CommandCollection;

class CommandCollectionSpec extends ObjectBehavior
{
    public function it_checks_command_type()
    {
        $this->shouldThrow(InvalidArgumentException::class)->during('addCommand', [stdClass::class]);
    }

    public function it_collects_commands()
    {
        $this->addCommand(StubCommand::class);
        $this->getCommands()->shouldContain(StubCommand::class);
    }

    function it_is_initializable()
    {
        $this->shouldImplement(CommandCollection::class);
    }
}
