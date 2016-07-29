<?php declare(strict_types = 1);

namespace Venta\Cache\Exception;

use Psr\Cache\InvalidArgumentException as InvalidArgumentExceptionContract;

/**
 * Class InvalidArgumentException
 *
 * @package Venta\Cache\Exception
 */
class InvalidArgumentException extends \Exception implements InvalidArgumentExceptionContract
{
    /**
     * Construct function
     *
     * @param mixed     $key
     * @param Exception $previous
     */
    public function __construct($key, Exception $previous = null)
    {
        parent::__construct(sprintf('%s is not a valid key.', $key), 0, $previous);
    }
}