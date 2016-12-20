<?php declare(strict_types = 1);

namespace Venta\Framework\ServiceProvider;

use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemInterface;
use Venta\Contracts\Filesystem\Filesystem as VentaFilesystemContract;
use Venta\Filesystem\Filesystem as VentaFilesystem;
use Venta\ServiceProvider\AbstractServiceProvider;

/**
 * Class FilesystemServiceProvider
 *
 * @package Venta\Framework\ServiceProvider
 */
class FilesystemServiceProvider extends AbstractServiceProvider
{
    /**
     * @inheritDoc
     */
    public function boot()
    {
        $this->container()->bindFactory(Filesystem::class, function () {
            return new Filesystem(new Local($this->kernel()->rootPath()));
        });
        $this->container()->bindClass(FilesystemInterface::class, Filesystem::class);
        $this->container()->bindClass(VentaFilesystemContract::class, VentaFilesystem::class);
    }

}