<?php declare(strict_types = 1);

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
            public function configure() {}

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
}
