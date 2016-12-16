<?php

namespace Venta\Console;

use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Console\Command\SignatureParser;

/**
 * Class AbstractCommand
 *
 * @package Venta\Console
 */
abstract class AbstractCommand extends SymfonyCommand
{
    /**
     * The command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * The command signature.
     *
     * @var string
     */
    protected $signature = '';

    /**
     * The Input implementation.
     *
     * @var InputInterface
     */
    private $input;

    /**
     * The Output implementation.
     *
     * @var OutputInterface
     */
    private $output;

    /**
     * {@inheritdoc}
     */
    final public function run(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;

        return parent::run($input, $output);
    }

    /**
     * {@inheritdoc}
     */
    final protected function configure()
    {
        $signature = (new SignatureParser())->parse($this->signature);

        $this->setName($signature['name']);
        $this->setDescription($this->description);

        foreach ($signature['arguments'] as $argument) {
            $this->addArgument(
                $argument['name'],
                $argument['type'],
                $argument['description'],
                $argument['default']
            );
        }

        foreach ($signature['options'] as $option) {
            $this->addOption($option['name'], null, $option['type'], $option['description'], $option['default']);
        }
    }

    /**
     * {@inheritdoc}
     */
    final protected function execute(InputInterface $input, OutputInterface $output)
    {
        return $this->handle();
    }

    /**
     * The command handler.
     *
     * @return int Exit code.
     */
    abstract protected function handle();

    /**
     * Returns input.
     *
     * @return InputInterface
     */
    protected function input(): InputInterface
    {
        return $this->input;
    }

    /**
     * Returns output.
     *
     * @return OutputInterface
     */
    protected function output(): OutputInterface
    {
        return $this->output;
    }
}