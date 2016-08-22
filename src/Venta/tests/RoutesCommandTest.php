<?php

class RoutesCommandTest extends PHPUnit_Framework_TestCase
{

    public function tearDown()
    {
        Mockery::close();
    }

    public function testHandle()
    {
        $application = Mockery::mock(\Venta\Contract\Application::class);
        $routeCollector = Mockery::mock(\Abava\Routing\Contract\Collector::class);
        $route = (new \Abava\Routing\Route(['GET','POST'], '/qwerty', 'callable'))
            ->withHost('localhost')
            ->withMiddleware('middleware1', function(){})
            ->withMiddleware('middleware2', function(){})
            ->withName('named')
            ->withScheme('http');
        $routeCollector->shouldReceive('getRoutes')->once()->andReturn([$route]);
        $application->shouldReceive('make')->with(\Abava\Routing\Contract\Collector::class)->andReturn($routeCollector);
        $application->shouldReceive('routes')->with($routeCollector);
        $table = Mockery::mock(\Symfony\Component\Console\Helper\Table::class);
        $table->shouldReceive('setHeaders');
        $table->shouldReceive('addRow')->once()->with([
            'GET,POST',
            '/qwerty',
            'callable',
            'named',
            'localhost',
            'http',
            'middleware1,middleware2'
        ]);
        $table->shouldReceive('render')->withNoArgs();
        $application->shouldReceive('make')->with(\Symfony\Component\Console\Helper\Table::class)->andReturn($table);

        $command = new \Venta\Commands\Routes($application);
        $input = new \Symfony\Component\Console\Input\ArrayInput([]);
        $output = new \Symfony\Component\Console\Output\BufferedOutput();
        $command->handle($input, $output);

        $this->assertEmpty($output->fetch());
    }


}
