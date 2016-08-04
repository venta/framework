<?php namespace Abava\Console;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CommandTest extends TestCase
{
    /**
     * @test
     */
    public function canHandle()
    {
        $command = new class() extends \Abava\Console\Command {

            public function handle(InputInterface $input, OutputInterface $output)
            {
                $this->write('abc');
            }

            public function signature(): string { return 'test'; }

        };

        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $command->run(new \Symfony\Component\Console\Input\ArrayInput([]), $output);
        $this->assertSame('abc', $output->fetch());
    }

    /**
     * @test
     */
    public function canArgumentAndOptionBeDefinedInSignature()
    {
        $command = new class() extends \Abava\Console\Command {

            public function handle(InputInterface $input, OutputInterface $output)
            {
                $this->write($this->arg('a'));
                $this->write($this->opt('o'));
            }

            public function signature(): string { return 'test {--o} {a}'; }

        };

        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $input = new \Symfony\Component\Console\Input\ArrayInput(['a'=>'argument','--o'=>'option']);
        $command->run($input, $output);
        $this->assertSame("argumentoption", $output->fetch());
    }

    /**
     * @test
     */
    public function canAcceptOptionsAndAgruments()
    {
        $command = new class() extends \Abava\Console\Command {

            public function handle(InputInterface $input, OutputInterface $output)
            {
                $this->write($this->arg('argument'));
                $this->write($this->opt('option'));
                $this->writeln('abc');
            }

            public function signature(): string { return 'test'; }

            public function returnArguments(): array
            {
                return [new InputArgument('argument')];
            }

            public function returnOptions(): array
            {
                return [new InputOption('option')];
            }

        };

        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $input = new \Symfony\Component\Console\Input\ArrayInput(['argument'=>'value1','--option'=>'value2']);
        $command->run($input, $output);
        $this->assertSame("value1value2abc\n", $output->fetch());
    }

}
