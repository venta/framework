<?php

use PHPUnit\Framework\TestCase;
use Venta\Console\Command\CommandCollector;
use Venta\Contracts\Container\Container;

/**
 * Class CollectorTest
 */
class CommandCollectorTest extends TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function canCollectCommands()
    {
        $container = Mockery::mock(Container::class);
        $mock = Mockery::mock(MockCommand::class);
        $container->shouldReceive('get')->with(MockCommand::class)->andReturn($mock)->once();

        $collector = new CommandCollector($container);
        $collector->addCommand(MockCommand::class);
        $result = $collector->getCommands();

        $this->assertSame([$mock], $result);
    }

    /**
     * @test
     */
    public function canHandleInvalidCommandClassName()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Provided command "stdClass" doesn\'t extend Venta\Console\Command class.');

        $collector = new CommandCollector(Mockery::mock(Container::class));
        $collector->addCommand(stdClass::class);
    }

}

abstract class MockCommand extends \Venta\Console\Command
{

}