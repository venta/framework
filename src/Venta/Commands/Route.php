<?php declare(strict_types = 1);

namespace Venta\Commands;

use Abava\Console\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Contract\Application;

/**
 * Class Route
 *
 * @package Venta\Commands
 */
class Route extends Command
{

    /**
     * @var Application
     */
    protected $app;

    public function __construct(Application $application)
    {
        parent::__construct();
        $this->app = $application;
    }

    /**
     * @inheritDoc
     */
    public function signature(): string
    {
        return 'routes';
    }

    /**
     * @inheritDoc
     */
    public function description(): string
    {
        return 'Lists application routes';
    }


    /**
     * @inheritDoc
     */
    public function handle(InputInterface $input, OutputInterface $output)
    {
        /** @var \Abava\Routing\Contract\Collector $collector */
        $collector = $this->app->make(\Abava\Routing\Contract\Collector::class);

        // Collect routes from extension providers
        $this->app->routes($collector);

        $table = $this->app->make(Table::class);
        $table->setHeaders(['Methods', 'Path', 'Action', 'Name', 'Host', 'Scheme', 'Middlewares']);
        foreach ($collector->getRoutes() as $route) {
            $table->addRow([
                join(',', $route->getMethods()),
                $route->getPath(),
                is_string($route->getCallable()) ? $route->getCallable() : get_class($route->getCallable()),
                $route->getName(),
                $route->getHost(),
                $route->getScheme(),
                join(',', array_keys($route->getMiddlewares())),
            ]);
        }
        $table->render();
    }

}