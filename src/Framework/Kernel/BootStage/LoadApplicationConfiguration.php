<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel\BootStage;

use Venta\Contracts\Config\Config;
use Venta\Contracts\Config\ConfigFactory;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Kernel\Kernel;
use Venta\Contracts\Kernel\KernelBootStage;

/**
 * Class LoadApplicationConfiguration
 *
 * @package Venta\Framework\Kernel\BootStage
 */
class LoadApplicationConfiguration implements KernelBootStage
{

    /**
     * @inheritDoc
     */
    public function run(Container $container)
    {
        /** @var Kernel $kernel */
        $kernel = $container->get('kernel');

        /** @var ConfigFactory $configFactory */
        $configFactory = $container->get(ConfigFactory::class);

        // todo: implement arbitrary config files loading.
        $config = $configFactory->createFromFile($kernel->getRootPath() . '/config/app.php');

        $container->set(Config::class, $config, ['config']);
    }
}