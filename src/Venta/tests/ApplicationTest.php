<?php declare(strict_types = 1);

use Venta\Contract\Application as ApplicationContract;

class ApplicationTest extends PHPUnit_Framework_TestCase
{

    /**
     * Application instance
     *
     * @var \Venta\Application
     */
    protected $application;

    public function setUp()
    {
        $this->application = new class(__DIR__, 'extensions.php') extends \Venta\Application{
            protected $version = 'test';
            public function configure()
            {
                $this->singleton(ApplicationContract::class, $this);
                $this->singleton('app', ApplicationContract::class);
                $this->singleton(\Abava\Container\Contract\Container::class, $this);
                $this->singleton(\Abava\Container\Contract\Caller::class, $this);

                $this->singleton(\Abava\Http\Contract\Request::class, $this->createServerRequest());
                $this->singleton(\Abava\Http\Factory\ResponseFactory::class, $this->createResponseFactory());
                $this->bindRouting();
            }

            public function callLoadExtensionProviders()
            {
                $this->loadExtensionProviders();
            }

            public function getExtensionProviders()
            {
                return $this->extensions;
            }

            public function addExtensionProviderInstance($name, $instance)
            {
                $this->extensions[$name] = $instance;
            }
        };
    }

    public function testEnvironment()
    {
        $this->assertSame(\Venta\Application::ENV_TEST, $this->application->environment());
        $this->assertTrue($this->application->isTestEnvironment());
        $this->assertFalse($this->application->isLiveEnvironment());
        $this->assertFalse($this->application->isLocalEnvironment());
        $this->assertFalse($this->application->isStageEnvironment());
    }

    public function testCli()
    {
        $this->assertTrue($this->application->isCli());
    }

    public function testVersion()
    {
        $this->assertSame('test', $this->application->version());
    }

    public function testLoadExtensions()
    {
        $this->assertEmpty($this->application->getExtensionProviders());
        $this->application->callLoadExtensionProviders();
        $extensions = $this->application->getExtensionProviders();
        $this->assertCount(1, $extensions);
        $this->assertInstanceOf(SampleExtension::class, reset($extensions));
    }

    public function testBootExtensionProviders()
    {
        $error_handler = new stdClass();
        $this->application->singleton('error_handler', $error_handler);
        $mock = $this->getMockBuilder(SampleExtension::class)->getMock();
        $mock->method('bindings')->with($this->application);
        $mock->method('errors')->with($error_handler);
        $this->assertFalse($this->application->isBooted());
        $this->application->addExtensionProviderInstance('Mock', $mock);
        $this->application->bootExtensionProviders();
        $this->assertTrue($this->application->isBooted());
    }

    public function testTerminate()
    {
        $this->application->bind('error_handler', stdClass::class);
        $this->application->bootExtensionProviders();
        $mock = $this->getMockBuilder(SampleExtension::class)->getMock();
        $mock->method('terminate')->with($this->application);
        $this->application->addExtensionProviderInstance('Mock', $mock);
        $this->application->terminate();
    }

    public function testBootstrap()
    {
        $this->assertTrue($this->application->has(ApplicationContract::class));
        $this->assertTrue($this->application->has('app'));
        $this->assertTrue($this->application->has(\Abava\Container\Contract\Container::class));
        $this->assertTrue($this->application->has(\Abava\Container\Contract\Caller::class));
        $this->assertTrue($this->application->has(\Abava\Http\Contract\Request::class));
        $this->assertTrue($this->application->has(\Abava\Http\Factory\ResponseFactory::class));
        $this->assertTrue($this->application->has(\Abava\Http\Contract\Emitter::class));
        $this->assertTrue($this->application->has(\FastRoute\RouteParser::class));
        $this->assertTrue($this->application->has(\FastRoute\DataGenerator::class));
        $this->assertTrue($this->application->has(\Abava\Routing\Contract\Collector::class));
        $this->assertTrue($this->application->has(\Abava\Routing\Contract\UrlGenerator::class));
        $this->assertTrue($this->application->has(\Abava\Routing\Contract\Middleware\Collector::class));
        $this->assertTrue($this->application->has(\Abava\Routing\Contract\Middleware\Pipeline::class));
        $this->assertTrue($this->application->has(\Abava\Routing\Contract\Matcher::class));
        $this->assertTrue($this->application->has(\Abava\Routing\Contract\Dispatcher\Factory::class));
        $this->assertTrue($this->application->has(\Abava\Routing\Contract\Strategy::class));
    }

    public function testRoutes()
    {
        $collector = Mockery::mock(\Abava\Routing\Contract\Collector::class);
        $provider = Mockery::mock(\Venta\Contract\ExtensionProvider\Routes::class);
        $collector->shouldReceive('group')->with('/', [$provider, 'routes'])->once();
        $this->application->addExtensionProviderInstance('route_provider', $provider);
        $this->application->routes($collector);
    }

    public function testMiddlewares()
    {
        $collector = Mockery::mock(\Abava\Routing\Contract\Middleware\Collector::class);
        $provider = Mockery::mock(\Venta\Contract\ExtensionProvider\Middlewares::class);
        $provider->shouldReceive('middlewares')->with($collector)->once();
        $this->application->addExtensionProviderInstance('middleware_provider', $provider);
        $this->application->middlewares($collector);
    }

    public function testCommands()
    {
        $console = Mockery::mock(\Symfony\Component\Console\Application::class);
        $provider = Mockery::mock(\Venta\Contract\ExtensionProvider\Commands::class);
        $provider->shouldReceive('commands')->with($console)->once();
        $this->application->addExtensionProviderInstance('command_provider', $provider);
        $this->application->commands($console);
    }

    public function tearDown()
    {
        Mockery::close();
    }

}
