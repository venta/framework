<?php declare(strict_types = 1);

namespace Venta\Commands;

use Abava\Console\Command;
use Abava\Routing\Contract\Collector;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Route
 *
 * @package Venta\Commands
 */
class Routes extends Command
{

    /**
     * @var Collector
     */
    protected $collector;

    /**
     * Routes constructor.
     *
     * @param Collector $collector
     */
    public function __construct(Collector $collector)
    {
        parent::__construct();
        $this->collector = $collector;
    }

    /**
     * @inheritDoc
     */
    public function signature(): string
    {
        return 'route:list';
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
        $routes = $this->collector->getRoutes();
        if (count($routes) == 0) {
            $this->writeln('<error>Application has no routes.</error>');
        } else {
            $table = new Table($output);
            $table->setHeaders(['Methods', 'Path', 'Action', 'Name', 'Host', 'Scheme', 'Middlewares']);
            foreach ($routes as $route) {
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

}