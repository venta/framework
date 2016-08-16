<?php declare(strict_types = 1);

namespace Venta\Commands;

use Abava\Console\Command;
use Abava\Http\Contract\Request;
use Abava\Routing\Contract\Matcher;
use Abava\Routing\Exceptions\NotAllowedException;
use Abava\Routing\Exceptions\NotFoundException;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Contract\Application;

/**
 * Class RouteMatch
 *
 * @package Venta\Commands
 */
class RouteMatch extends Command
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
        return 'route:match {path:Uri path to match against} {--method=GET:Specify request method} {--host=:Specify request host} {--scheme=:Specify request scheme}';
    }

    /**
     * @inheritDoc
     */
    public function description(): string
    {
        return 'Matches provided path against application routes';
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

        /** @var Matcher $matcher */
        $matcher = $this->app->make(Matcher::class);
        /** @var Request $request */
        $request = $this->app->make(Request::class);
        $request = $request->withUri($request->getUri()->withPath($this->arg('path')));
        if ($input->getOption('method')) {
            $request = $request->withMethod($input->getOption('method'));
        }
        if ($input->getOption('host')) {
            $request = $request->withUri($request->getUri()->withHost($input->getOption('host')));
        }
        if ($input->getOption('scheme')) {
            $request = $request->withUri($request->getUri()->withScheme($input->getOption('scheme')));
        }
        try {
            $route = $matcher->match($request, $collector);
            /** @var Table $table */
            $table = $this->app->make(Table::class);
            $table->setHeaders(['Methods', 'Path', 'Action', 'Name', 'Host', 'Scheme', 'Middlewares']);
            $table->addRow([
                join(',', $route->getMethods()),
                $route->getPath(),
                is_string($route->getCallable()) ? $route->getCallable() : get_class($route->getCallable()),
                $route->getName(),
                $route->getHost(),
                $route->getScheme(),
                join(',', array_keys($route->getMiddlewares())),
            ]);
            $table->render();
        } catch (NotFoundException $e) {
            $output->writeln('<error>Path cannot be matched against defined routes</error>');
        } catch (NotAllowedException $e) {
            $output->writeln('<error>' . $e->getMessage() . '</error>');
        }
    }


}