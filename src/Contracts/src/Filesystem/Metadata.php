<?php declare(strict_types = 1);

namespace Venta\Contracts\Filesystem;

use DateTimeInterface;

/**
 * Interface Metadata
 *
 * @package Venta\Contracts\Filesystem
 */
interface Metadata
{

    const TYPE_FILE = 'file', TYPE_DIR = 'dir';

    /**
     * Returns file mime-type.
     *
     * @return string
     */
    public function mimetype(): string;

    /**
     * Returns file size.
     *
     * @return int
     */
    public function size(): int;

    /**
     * Returns file modification date.
     *
     * @return DateTimeInterface|null
     */
    public function timestamp();

    /**
     * Returns object type: "file" or "dir".
     *
     * @return string
     */
    public function type(): string;

    /**
     * Returns path to the object.
     *
     * @return string
     */
    public function path(): string;
}