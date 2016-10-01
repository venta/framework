<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel;

use Psr\Log\LoggerAwareInterface;
use ReflectionObject;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Output\OutputInterface;
use Venta\Config\ConfigFactory;
use Venta\Console\Command\CommandCollector as CommandCollector;
use Venta\Contracts\Config\ConfigFactory as ConfigFactoryContract;
use Venta\Contracts\Console\CommandCollector as CommandCollectorContract;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Container\ContainerAware;
use Venta\Contracts\Http\ResponseFactory as ResponseFactoryContract;
use Venta\Contracts\Kernel\Kernel as KernelContract;
use Venta\Contracts\Kernel\KernelBootStage;
use Venta\Contracts\Routing\MiddlewareCollector as MiddlewareCollectorContract;
use Venta\Contracts\Routing\RouteCollector as RouteCollectorContract;
use Venta\Framework\Kernel\BootStage\BootServiceProviders;
use Venta\Framework\Kernel\BootStage\DetectEnvironment;
use Venta\Framework\Kernel\BootStage\LoadApplicationConfiguration;
use Venta\Framework\Kernel\BootStage\RegisterErrorHandler;
use Venta\Http\Factory\ResponseFactory;
use Venta\Routing\Middleware\MiddlewareCollector as MiddlewareCollector;
use Venta\Routing\RouteCollector as RouteCollector;

/**
 * Class Kernel
 *
 * @package Venta
 */
class Kernel implements KernelContract
{
    const VERSION = '0.1.0';

    /**
     * Service container instance.
     *
     * @var Container
     */
    protected $container;

    /**
     * Project root absolute path
     *
     * @var string
     */
    protected $rootPath;

    /**
     * @var KernelBootStage[]
     */
    private $bootStages = [
        DetectEnvironment::class,
        LoadApplicationConfiguration::class,
        RegisterErrorHandler::class,
        BootServiceProviders::class,
    ];

    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->initializeServiceContainer();

        foreach ($this->bootStages as $bootStageClass) {
            /** @var KernelBootStage $bootStage */
            $bootStage = new $bootStageClass;
            $bootStage->run($this->container);
        }
    }

    /**
     * @inheritDoc
     */
    public function getContainer(): Container
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function getEnvironment(): string
    {
        return getenv('APP_ENV') ? getenv('APP_ENV') : 'local';
    }

    /**
     * @return string
     */
    public function getRootPath(): string
    {
        if ($this->rootPath === null) {
            $reflectionObject = new ReflectionObject($this);
            $this->rootPath = dirname($reflectionObject->getFileName());
        }

        return $this->rootPath;
    }

    /**
     * @inheritDoc
     */
    public function getVersion(): string
    {
        return self::VERSION;
    }

    /**
     * @inheritDoc
     */
    public function isCli(): bool
    {
        return php_sapi_name() === 'cli';
    }

    /**
     * Returns default service container class name.
     *
     * @return string
     */
    protected function getServiceContainerClass(): string
    {
        return \Venta\Container\Container::class;
    }

    /**
     * Initializes service container.
     */
    protected function initializeServiceContainer()
    {
        $class = $this->getServiceContainerClass();

        $this->container = new $class;

        // todo: split across corresponding core service providers.
        // Register base services.
        $this->container->share(KernelContract::class, $this, ['kernel']);

//        $this->container->share(
//            ServerRequestInterface::class,
//            [RequestFactoryContract::class, 'createServerRequestFromGlobals'],
//            ['request', RequestInterface::class, Request::class]
//        );

        $this->container->share(ResponseFactoryContract::class, ResponseFactory::class);
        $this->container->share(InputInterface::class, new ArgvInput(), ['console.input']);
        $this->container->share(OutputInterface::class, new ConsoleOutput(), ['console.output']);
        $this->container->share(RouteCollectorContract::class, RouteCollector::class);
        $this->container->share(MiddlewareCollectorContract::class, MiddlewareCollector::class);
        $this->container->share(CommandCollectorContract::class, CommandCollector::class);
        $this->container->share(ConfigFactoryContract::class, ConfigFactory::class);

        // Register default inflections.
        $this->container->inflect(ContainerAware::class, 'setContainer', ['container' => $this->container]);
        $this->container->inflect(LoggerAwareInterface::class, 'setLogger');
    }
}