<?php

namespace spec\Venta\Http;

use PhpSpec\ObjectBehavior;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Config\Config;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Kernel\Kernel;
use Venta\Contracts\Routing\Delegate;
use Venta\Contracts\Routing\MiddlewarePipeline;
use Venta\Contracts\Routing\MiddlewarePipelineFactory;
use Venta\Contracts\Routing\Router;
use Venta\Http\HttpApplication;

class HttpApplicationSpec extends ObjectBehavior
{

    function let(Kernel $kernel)
    {
        $this->beConstructedWith($kernel);
    }

    function it_is_initializable_and_boots_kernel(Kernel $kernel, Container $container)
    {
        $kernel->boot()->willReturn($container)->shouldBeCalled();
        $this->shouldHaveType(HttpApplication::class);
        $this->shouldImplement(Delegate::class);
    }

    function it_runs(
        Kernel $kernel,
        Container $container,
        ServerRequestInterface $request,
        ResponseInterface $response,
        Config $config,
        MiddlewarePipelineFactory $factory,
        Router $router,
        MiddlewarePipeline $pipeline
    ) {
        $kernel->boot()->willReturn($container);
        $container->get(Config::class)->willReturn($config);
        $container->get(Router::class)->willReturn($router);
        $container->get(MiddlewarePipelineFactory::class)->willReturn($factory);

        $config->get('middlewares', [])->willReturn([]);
        $factory->create([])->willReturn($pipeline);
        $pipeline->process($request, $router)->willReturn($response);

        $this->run($request)->shouldBe($response);
        $this->next($request)->shouldBe($response);
    }
}
