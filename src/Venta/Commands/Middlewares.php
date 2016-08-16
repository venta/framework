<?php declare(strict_types = 1);

namespace Venta\Commands;

use Abava\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Contract\Application;

/**
 * Class Middlewares
 *
 * @package Venta\Commands
 */
class Middlewares extends Command
{

    /**
     * @var Application
     */
    protected $app;

    /**
     * Middlewares constructor.
     *
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        $this->app = $application;
        parent::__construct();
    }

    /**
     * @inheritDoc
     */
    public function signature(): string
    {
        return 'middlewares';
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
        /** @var \Abava\Routing\Contract\Middleware\Collector $collector */
        $collector = $this->app->make(\Abava\Routing\Contract\Middleware\Collector::class);

        // Collect routes from extension providers
        $this->app->middlewares($collector);

        $table = $this->app->make(Table::class);
        $table->setHeaders(['Pos', 'Name', 'Type']);
        $i=0;
        foreach ($collector as $name => $middleware) {
            $table->addRow([++$i, $name, get_class($middleware)]);
        }
        $table->render();
    }

}