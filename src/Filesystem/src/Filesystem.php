<?php declare(strict_types = 1);

namespace Venta\Filesystem;

use League\Flysystem\FilesystemInterface;
use League\Flysystem\UnreadableFileException;
use Venta\Contracts\Filesystem\Filesystem as FilesystemContract;
use Venta\Contracts\Filesystem\Metadata as MetadataContract;

/**
 * Class Filesystem
 *
 * @package Venta\Filesystem
 */
class Filesystem implements FilesystemContract
{

    /**
     * @var FilesystemInterface
     */
    private $flysystem;

    /**
     * Filesystem constructor.
     *
     * @param FilesystemInterface $flysystem
     */
    public function __construct(FilesystemInterface $flysystem)
    {
        $this->flysystem = $flysystem;
    }

    /**
     * @inheritDoc
     */
    public function append(string $path, string $contents, array $config = []): bool
    {
        if ($this->exists($path)) {
            $contents = $this->read($path) . $contents;
        }

        return $this->write($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function copy(string $path, string $newPath): bool
    {
        return $this->flysystem->copy($path, $newPath);
    }

    /**
     * @inheritDoc
     */
    public function createDir(string $dirname, array $config = []): bool
    {
        return $this->flysystem->createDir($dirname, $config);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $path): bool
    {
        return $this->flysystem->delete($path);
    }

    /**
     * @inheritDoc
     */
    public function deleteDir(string $dirname): bool
    {
        return $this->flysystem->deleteDir($dirname);
    }

    /**
     * @inheritDoc
     */
    public function exists(string $path): bool
    {
        return $this->flysystem->has($path);
    }

    /**
     * @inheritDoc
     */
    public function listAll(string $path = '.', bool $recursive = false): array
    {
        return array_map(
            function ($item) {
                return new Metadata($item);
            },
            $this->flysystem->listContents($path, $recursive)
        );
    }

    /**
     * @inheritDoc
     */
    public function listDirectories(string $path = '.', bool $recursive = false): array
    {
        return array_map(
            function ($item) {
                return new Metadata($item);
            },
            array_filter(
                $this->flysystem->listContents($path, $recursive),
                function ($item) {
                    return $item['type'] === MetadataContract::TYPE_DIR;
                }
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function listFiles(string $path = '.', bool $recursive = false): array
    {
        return array_map(
            function ($item) {
                return new Metadata($item);
            },
            array_filter(
                $this->flysystem->listContents($path, $recursive),
                function ($item) {
                    return $item['type'] === MetadataContract::TYPE_FILE;
                }
            )
        );
    }

    /**
     * @inheritDoc
     */
    public function metadata($path)
    {
        $metadata = $this->flysystem->getMetadata($path);
        if (is_array($metadata)) {
            return new Metadata($metadata);
        }

        return null;
    }

    /**
     * @inheritDoc
     */
    public function move(string $path, string $newPath): bool
    {
        return $this->flysystem->rename($path, $newPath);
    }

    /**
     * @inheritDoc
     */
    public function prepend(string $path, string $contents, array $config = []): bool
    {
        if ($this->exists($path)) {
            $contents .= $this->read($path);
        }

        return $this->write($path, $contents);
    }

    /**
     * @inheritDoc
     */
    public function read(string $path): string
    {
        $content = $this->flysystem->read($path);
        if ($content === false) {
            throw new UnreadableFileException(sprintf('Unable to read file "%s".', $path));
        }

        return $content;
    }

    /**
     * @inheritDoc
     */
    public function write(string $path, string $contents, array $config = []): bool
    {
        return $this->flysystem->put($path, $contents, $config);
    }

}