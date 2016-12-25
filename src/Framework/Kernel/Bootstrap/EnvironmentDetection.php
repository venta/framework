<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\Bootstrap;

use Dotenv\Loader;
use Symfony\Component\Console\Input\ArgvInput;
use Venta\Framework\Kernel\AbstractKernelBootstrap;

/**
 * Class EnvironmentDetection
 *
 * @package Venta\Framework\Kernel\Bootstrap
 */
final class EnvironmentDetection extends AbstractKernelBootstrap
{
    /**
     * @inheritDoc
     */
    public function __invoke()
    {
        $filePath = rtrim($this->kernel()->rootPath(), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '.env';

        $envLoader = new Loader($filePath);
        $envLoader->load();

        if ($this->kernel()->isCli()) {
            // todo: Load different env fire for each environment.
            $env = (new ArgvInput)->getParameterOption(['--env', '-e']);
            if ($env) {
                $envLoader->setEnvironmentVariable('APP_ENV', $env);
            }
        }
    }

}