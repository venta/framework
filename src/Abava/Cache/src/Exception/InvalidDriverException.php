<?php declare(strict_types = 1);

namespace Venta\Cache\Exception;

use Psr\Cache\CacheException;

/**
 * Class InvalidArgumentException
 *
 * @package Venta\Cache\Exception
 */
class InvalidDriverException extends \Exception implements CacheException
{
    /**
     * Construct function
     *
     * @param string     $driver
     * @param \Exception $previous
     */
    public function __construct(string $driver, \Exception $previous = null)
    {
        parent::__construct(sprintf('"%s" class for cache driver can not be found.', $driver), 0, $previous);
    }
}