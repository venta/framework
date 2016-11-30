<?php

class RoutesCommandTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     */
    public function canHandleEmptyRouteArray()
    {
        $routeCollector = Mockery::mock(\Venta\Contracts\Routing\RouteCollector::class);
        $routeCollector->shouldReceive('getRoutes')->once()->andReturn([]);

        $command = new \Venta\Framework\Commands\Routes($routeCollector);
        $input = new \Symfony\Component\Console\Input\ArrayInput([]);
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $command->run($input, $output);
        $result = $output->fetch();

        $this->assertContains('Application has no routes.', $result);
    }

    /**
     * @ignore
     */
    public function canListRoutes()
    {
        $routeCollector = Mockery::mock(\Venta\Contracts\Routing\RouteCollector::class);
        $route = (new \Venta\Routing\Route(['GET', 'POST'], '/qwerty', 'callable'))
            ->withHost('localhost')
            ->withMiddleware('middleware1', function () {
            })
            ->withMiddleware('middleware2', function () {
            })
            ->withName('named')
            ->withScheme('http');
        $routeCollector->shouldReceive('getRoutes')->once()->andReturn([$route]);

        $command = new \Venta\Framework\Commands\Routes($routeCollector);
        $input = new \Symfony\Component\Console\Input\ArrayInput([]);
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $command->run($input, $output);
        $result = $output->fetch();

        $this->assertContains('callable', $result);
    }

}
