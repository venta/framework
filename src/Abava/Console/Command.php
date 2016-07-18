<?php

namespace Abava\Console;

use Abava\Console\Command\SignatureParser;
use Abava\Console\Contract\Command as CommandContract;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\{
    InputArgument, InputInterface, InputOption
};
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Command
 *
 * @package Abava\Console
 */
abstract class Command extends BaseCommand implements CommandContract
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
     * {@inheritdoc}
     */
    public function configure()
    {
        $signature = (new SignatureParser())->parse($this->signature());

        $this->setName($signature['name']);
        $this->setDescription($this->description());

        if (is_array($signature['arguments']) && count($signature['arguments']) > 0) {
            foreach ($signature['arguments'] as $argument) {
                $this->addArgument($argument['name'], $argument['type'], $argument['description'], $argument['default']);
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
    final protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        return $this->handle($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    public function description(): string
    {
        return '';
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
     * Helper method to get input argument
     *
     * @param string $name
     * @return mixed
     */
    public function arg(string $name)
    {
        return $this->input->getArgument($name);
    }

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
     * Returns command arguments array
     * Values must be instances of InputArgument
     *
     * @return array|InputArgument[]
     */
    public function returnArguments(): array
    {
        return [];
    }

    /**
     * Returns command options array
     * Values must be instances of InputOption
     *
     * @return array|InputOption[]
     */
    public function returnOptions(): array
    {
        return [];
    }

}