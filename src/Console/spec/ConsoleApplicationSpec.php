<?php

namespace spec\Venta\Console;

use PhpSpec\ObjectBehavior;
use Symfony\Component\Console\Application;
use Venta\Console\ConsoleApplication;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Kernel\Kernel;


class ConsoleApplicationSpec extends ObjectBehavior
{

    function it_is_initializable(Kernel $kernel, Container $container, Application $symfonyConsoleApplication)
    {
        $kernel->boot()->shouldBeCalledTimes(1)->willReturn($container);
        $kernel->version()->shouldBeCalled();
        $container->get(Application::class)->willReturn($symfonyConsoleApplication);

        $this->beConstructedWith($kernel);
        $this->shouldHaveType(ConsoleApplication::class);
    }

}
