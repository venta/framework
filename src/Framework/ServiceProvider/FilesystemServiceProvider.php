<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Venta\Contracts\Container\MutableContainer;
use Venta\Contracts\Filesystem\Filesystem as VentaFilesystemContract;
use Venta\Filesystem\Filesystem as VentaFilesystem;
use Venta\ServiceProvider\AbstractServiceProvider;

/**
 * Class FilesystemServiceProvider
 *
 * @package Venta\Framework\ServiceProvider
 */
final class FilesystemServiceProvider extends AbstractServiceProvider
{
    /**
     * @inheritDoc
     */
    public function bind(MutableContainer $container)
    {
        $container->factory(Filesystem::class, function () {
            return new Filesystem(new Local($this->kernel()->rootPath()));
        });
        $container->bind(FilesystemInterface::class, Filesystem::class);
        $container->bind(VentaFilesystemContract::class, VentaFilesystem::class);
    }

}