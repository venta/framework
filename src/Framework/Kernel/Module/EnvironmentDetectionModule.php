<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\Module;

use Dotenv\Loader;
use Symfony\Component\Console\Input\InputInterface;
use Venta\Framework\Kernel\AbstractKernelModule;

/**
 * Class EnvironmentDetectionModule
 *
 * @package Venta\Framework\Kernel\Module
 */
class EnvironmentDetectionModule extends AbstractKernelModule
{
    /**
     * @inheritDoc
     */
    public function init()
    {
        $filePath = rtrim($this->kernel->getRootPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '.env';

        $envLoader = new Loader($filePath);
        if ($this->kernel->isCli()) {
            /** @var InputInterface $consoleInput */
            $consoleInput = $this->container->get(InputInterface::class);
            $env = $consoleInput->getParameterOption(['--env', '-e']);
            if ($env) {
                $envLoader->setEnvironmentVariable('APP_ENV', $env);
            }
        }
    }

}