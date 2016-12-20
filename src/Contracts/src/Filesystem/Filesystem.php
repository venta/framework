<?php declare(strict_types = 1);

namespace Venta\Contracts\Filesystem;

/**
 * Interface Filesystem
 *
 * @package Venta\Contracts\Filesystem
 */
interface Filesystem
{

    /**
     * Append contents to a file.
     *
     * @param string $path
     * @param string $contents
     * @param array $config
     * @return bool True on success, false on failure.
     */
    public function append(string $path, string $contents, array $config = []): bool;

    /**
     * Copy a file.
     *
     * @param string $path Path to the existing file.
     * @param string $newPath
     * @return bool True on success, false on failure.
     */
    public function copy(string $path, string $newPath): bool;

    /**
     * Create a directory.
     *
     * @param string $dirname The name of the new directory.
     * @param array $config An optional configuration array.
     * @return bool True on success, false on failure.
     */
    public function createDir(string $dirname, array $config = []): bool;

    /**
     * Delete a file.
     *
     * @param string $path
     * @return bool True on success, false on failure.
     */
    public function delete(string $path): bool;

    /**
     * Delete a directory.
     *
     * @param string $dirname
     * @return bool True on success, false on failure.
     */
    public function deleteDir(string $dirname): bool;

    /**
     * Check whether a file exists.
     *
     * @param string $path
     * @return bool
     */
    public function exists(string $path): bool;

    /**
     * List contents of a directory.
     *
     * @param string $path The directory to list.
     * @param bool $recursive Whether to list recursively.
     *
     * @return Metadata[] A list of file metadata.
     */
    public function list(string $path = '.', bool $recursive = false): array;

    /**
     * List directories in a directory.
     *
     * @param string $path The directory to list.
     * @param bool $recursive Whether to list recursively.
     *
     * @return Metadata[] A list of file metadata.
     */
    public function listDirectories(string $path = '.', bool $recursive = false): array;

    /**
     * List files in a directory.
     *
     * @param string $path The directory to list.
     * @param bool $recursive Whether to list recursively.
     *
     * @return Metadata[] A list of file metadata.
     */
    public function listFiles(string $path = '.', bool $recursive = false): array;

    /**
     * Get a file's metadata.
     *
     * @param string $path The path to the file.
     * @return Metadata|null The file metadata.
     */
    public function metadata($path);

    /**
     * Rename a file.
     *
     * @param string $path Path to the existing file.
     * @param string $newPath
     * @return bool True on success, false on failure.
     */
    public function move(string $path, string $newPath): bool;

    /**
     * Prepend contents to a file.
     *
     * @param string $path
     * @param string $contents
     * @param array $config
     * @return bool True on success, false on failure.
     */
    public function prepend(string $path, string $contents, array $config = []): bool;

    /**
     * Read a file.
     *
     * @param string $path The path to the file.
     * @return string The file contents.
     */
    public function read(string $path): string;

    /**
     * Write a new file.
     *
     * @param string $path The path of the new file.
     * @param string $contents The file contents.
     * @param array $config An optional configuration array.
     * @return bool True on success, false on failure.
     */
    public function write(string $path, string $contents, array $config = []): bool;

}