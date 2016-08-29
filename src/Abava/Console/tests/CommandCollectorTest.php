<?php

use Abava\Console\Command\Collector;
use Abava\Container\Contract\Container;
use PHPUnit\Framework\TestCase;

/**
 * Class CollectorTest
 */
class CommandCollectorTest extends TestCase
{

    /**
     * @test
     */
    public function canCollectCommands()
    {
        $container = Mockery::mock(Container::class);
        $mock = Mockery::mock(MockCommand::class);
        $container->shouldReceive('get')->with(MockCommand::class)->andReturn($mock)->once();

        $collector = new Collector($container);
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
        $this->expectExceptionMessage('Provided command "stdClass" doesn\'t extend Abava\Console\Command class.');

        $collector = new Collector(Mockery::mock(Container::class));
        $collector->addCommand(stdClass::class);
    }

    public function tearDown()
    {
        Mockery::close();
    }

}

abstract class MockCommand extends \Abava\Console\Command
{

}