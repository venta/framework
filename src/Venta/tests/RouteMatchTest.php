<?php

use Abava\Http\Contract\RequestFactory;
use Abava\Routing\Contract\Collector;
use Abava\Routing\Contract\Matcher;
use Abava\Routing\Exceptions\NotAllowedException;
use Abava\Routing\Exceptions\NotFoundException;
use Abava\Routing\Route;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;
use Venta\Commands\RouteMatch;

/**
 * Class RouteMatchTest
 */
class RouteMatchTest extends TestCase
{

    public function tearDown()
    {
        Mockery::mock();
    }

    /**
     * @test
     */
    public function canHandleNotAllowedException()
    {
        // Mocking stubs
        $collector = Mockery::mock(Collector::class);
        $matcher = Mockery::mock(Matcher::class);
        $requestFactory = Mockery::mock(RequestFactory::class);
        $request = Mockery::mock(ServerRequestInterface::class);

        // Defining mock methods
        $requestFactory->shouldReceive('createServerRequest')->andReturn($request);
        $matcher->shouldReceive('match')->with($request, $collector)->andThrow(new NotAllowedException(['GET']));

        // Creating and running command
        $command = new RouteMatch($collector, $matcher, $requestFactory);
        $input = new ArrayInput(['path' => '/']);
        $output = new BufferedOutput();
        $command->run($input, $output);

        // Assert command output contains matched route
        $this->assertContains('Method is not allowed', $output->fetch());
    }

    /**
     * @test
     */
    public function canHandleNotFoundException()
    {
        // Mocking stubs
        $collector = Mockery::mock(Collector::class);
        $matcher = Mockery::mock(Matcher::class);
        $requestFactory = Mockery::mock(RequestFactory::class);
        $request = Mockery::mock(ServerRequestInterface::class);

        // Defining mock methods
        $requestFactory->shouldReceive('createServerRequest')->andReturn($request);
        $matcher->shouldReceive('match')->with($request, $collector)->andThrow(new NotFoundException());

        // Creating and running command
        $command = new RouteMatch($collector, $matcher, $requestFactory);
        $input = new ArrayInput(['path' => '/non-existing-path']);
        $output = new BufferedOutput();
        $command->run($input, $output);

        // Assert command output contains matched route
        $this->assertContains('Path cannot be matched against defined routes', $output->fetch());
    }

    /**
     * @test
     */
    public function canMatchAgainstHostAndScheme()
    {
        // Mocking stubs
        $collector = Mockery::mock(Collector::class);
        $matcher = Mockery::mock(Matcher::class);
        $requestFactory = Mockery::mock(RequestFactory::class);
        $request = Mockery::mock(ServerRequestInterface::class);

        // Defining mock methods
        $route = new Route(['GET'], '/', 'handle');
        $requestFactory->shouldReceive('createServerRequest')
                       ->with('POST', Mockery::on(function (UriInterface $uri) {
                           return $uri->getPath() === '/'
                                  && $uri->getHost() === 'localhost'
                                  && $uri->getScheme() === 'https';
                       }))
                       ->andReturn($request);
        $matcher->shouldReceive('match')->with($request, $collector)->andReturn($route);

        // Creating and running command
        $command = new RouteMatch($collector, $matcher, $requestFactory);
        $input = new ArrayInput(['path' => '/', '--host' => 'localhost', '--scheme' => 'https', '--method' => 'POST']);
        $output = new BufferedOutput();
        $command->run($input, $output);

        // Assert command output contains matched route
        $this->assertContains('handle', $output->fetch());
    }

    /**
     * @test
     */
    public function canMatchRoute()
    {
        // Mocking stubs
        $collector = Mockery::mock(Collector::class);
        $matcher = Mockery::mock(Matcher::class);
        $requestFactory = Mockery::mock(RequestFactory::class);
        $request = Mockery::mock(ServerRequestInterface::class);

        // Defining mock methods
        $route = new Route(['GET'], '/', 'handle');
        $requestFactory->shouldReceive('createServerRequest')->andReturn($request);
        $matcher->shouldReceive('match')->with($request, $collector)->andReturn($route);

        // Creating and running command
        $command = new RouteMatch($collector, $matcher, $requestFactory);
        $input = new ArrayInput(['path' => '/']);
        $output = new BufferedOutput();
        $command->run($input, $output);

        // Assert command output contains matched route
        $this->assertContains('handle', $output->fetch());
    }

}
