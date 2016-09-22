<?php declare(strict_types = 1);

namespace Venta\Framework\Commands;

use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Console\Command;
use Venta\Contracts\Routing\MiddlewareCollector;

/**
 * Class Middlewares
 *
 * @package Venta\Commands
 */
class Middlewares extends Command
{

    /**
     * @var \Venta\Contracts\Routing\MiddlewareCollector
     */
    protected $collector;

    /**
     * Middlewares constructor.
     *
     * @param \Venta\Contracts\Routing\MiddlewareCollector $collector
     */
    public function __construct(MiddlewareCollector $collector)
    {
        parent::__construct();
        $this->collector = $collector;
    }

    /**
     * @inheritDoc
     */
    public function description(): string
    {
        return 'Outputs middleware list';
    }

    /**
     * @inheritDoc
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        $table = new Table($output);
        $table->setHeaders(['#', 'Name', 'Type']);
        $i = 0;
        foreach ($this->collector as $name => $middleware) {
            $table->addRow([++$i, $name, get_class($middleware)]);
        }
        $table->render();
    }

    /**
     * @inheritDoc
     */
    public function signature(): string
    {
        return 'middleware:list';
    }

}