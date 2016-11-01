<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\Bootstrap;

use Dotenv\Loader;
use Symfony\Component\Console\Input\InputInterface;
use Venta\Framework\Kernel\AbstractKernelBootstrap;

/**
 * Class EnvironmentDetection
 *
 * @package Venta\Framework\Kernel\Bootstrap
 */
class EnvironmentDetection extends AbstractKernelBootstrap
{
    /**
     * @inheritDoc
     */
    public function boot()
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