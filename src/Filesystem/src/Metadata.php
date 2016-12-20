<?php declare(strict_types = 1);

namespace Venta\Filesystem;

use DateTime;
use DateTimeInterface;
use Venta\Contracts\Filesystem\Metadata as MetadataContract;

/**
 * Class Metadata
 *
 * @package Venta\Filesystem
 */
class Metadata implements MetadataContract
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * Metadata constructor.
     *
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @inheritDoc
     */
    public function mimetype(): string
    {
        return $this->data['mimetype'] ?? '';
    }

    /**
     * @inheritDoc
     */
    public function size(): int
    {
        return $this->data['size'] ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function timestamp(): DateTimeInterface
    {
        if (!isset($this->data['timestamp'])) {
            return null;
        }

        if ($this->data['timestamp'] instanceof DateTimeInterface) {
            return $this->data['timestamp'];
        }

        return (new DateTime())->setTimestamp($this->data['timestamp']);
    }

    /**
     * @inheritDoc
     */
    public function type(): string
    {
        return $this->data['type'] ?? '';
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return $this->data['path'] ?? '';
    }

}