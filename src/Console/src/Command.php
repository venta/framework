<?php

namespace Venta\Console;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Console\Command\SignatureParser;

/**
 * Class Command
 *
 * @package Venta\Console
 */
abstract class Command extends BaseCommand
{

    /**
     * Input instance passed to handle method
     *
     * @var InputInterface
     */
    protected $input;

    /**
     * Output instance passed to handle method
     *
     * @var OutputInterface
     */
    protected $output;

    /**
     * Helper method to get input argument
     *
     * @param string $name
     * @return string
     */
    public function arg(string $name)
    {
        return $this->input->getArgument($name);
    }

    /**
     * {@inheritdoc}
     */
    public function configure()
    {
        $signature = (new SignatureParser())->parse($this->signature());

        $this->setName($signature['name']);
        $this->setDescription($this->description());

        if (is_array($signature['arguments']) && count($signature['arguments']) > 0) {
            foreach ($signature['arguments'] as $argument) {
                $this->addArgument(
                    $argument['name'],
                    $argument['type'],
                    $argument['description'],
                    $argument['default']
                );
            }
        } else {
            $this->getDefinition()->addArguments($this->returnArguments());
        }

        if (is_array($signature['options']) && count($signature['options']) > 0) {
            foreach ($signature['options'] as $option) {
                $this->addOption($option['name'], null, $option['type'], $option['description'], $option['default']);
            }
        } else {
            $this->getDefinition()->addOptions($this->returnOptions());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function description(): string
    {
        return '';
    }

    /**
     * Main command function, which is executed on command run
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return null|int
     */
    abstract public function handle(InputInterface $input, OutputInterface $output);

    /**
     * Helper method to get input option
     *
     * @param string $name
     * @return mixed
     */
    public function opt(string $name)
    {
        return $this->input->getOption($name);
    }

    /**
     * Returns command arguments array
     * Values must be instances of InputArgument
     *
     * @return InputArgument[]
     */
    public function returnArguments(): array
    {
        return [];
    }

    /**
     * Returns command options array
     * Values must be instances of InputOption
     *
     * @return InputOption[]
     */
    public function returnOptions(): array
    {
        return [];
    }

    /**
     * Making method final to restrict overwrite
     *
     * {@inheritdoc}
     */
    final public function run(InputInterface $input, OutputInterface $output): int
    {
        return parent::run($input, $output);
    }

    /**
     * Should return string with command signature
     *
     * @return string
     */
    abstract public function signature(): string;

    /**
     * Helper method to write string to output
     *
     * @param string $string
     * @param bool $newline
     * @param int $options
     * @return void
     */
    public function write(string $string, bool $newline = false, int $options = 0)
    {
        $this->output->write($string, $newline, $options);
    }

    /**
     * Helper method to write string with new line to output
     *
     * @param string $string
     * @param int $options
     * @return void
     */
    public function writeln(string $string, int $options = 0)
    {
        $this->output->writeln($string, $options);
    }

    /**
     * {@inheritdoc}
     */
    final protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        return $this->handle($input, $output);
    }

}