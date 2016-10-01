<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\BootStage;

use Dotenv\Loader;
use Symfony\Component\Console\Input\InputInterface;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Kernel\Kernel;
use Venta\Contracts\Kernel\KernelBootStage;

/**
 * Class DetectEnvironment
 *
 * @package Venta\Framework\Kerbel\BootStage
 */
class DetectEnvironment implements KernelBootStage
{
    /**
     * @inheritDoc
     */
    public function run(Container $container)
    {
        /** @var Kernel $kernel */
        $kernel = $container->get('kernel');

        $filePath = rtrim($kernel->getRootPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '.env';

        $envLoader = new Loader($filePath);
        if ($kernel->isCli()) {
            /** @var InputInterface $consoleInput */
            $consoleInput = $container->get(InputInterface::class);
            $env = $consoleInput->getParameterOption(['--env', '-e']);
            if ($env) {
                $envLoader->setEnvironmentVariable('APP_ENV', $env);
            }
        }
    }

}