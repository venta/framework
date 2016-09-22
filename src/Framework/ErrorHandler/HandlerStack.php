<?php declare(strict_types = 1);

namespace Venta\Framework\ErrorHandler;

use Venta\Contracts\ErrorHandler\HandlerStack as HandlerStackContract;
use Venta\Contracts\ErrorHandler\ThrowableHandler;

/**
 * Class HandlerStack
 *
 * @package Venta\Framework\ErrorHandler
 */
class HandlerStack implements HandlerStackContract
{
    /**
     * @var ThrowableHandler[]
     */
    protected $stack = [];

    /**
     * HandlerStack constructor. Requires initial handler.
     *
     * @param ThrowableHandler $handler
     */
    public function __construct(ThrowableHandler $handler)
    {
        $this->push($handler);
    }

    /**
     * @inheritDoc
     */
    public function current()
    {
        return current($this->stack);
    }

    /**
     * @inheritDoc
     */
    public function key()
    {
        return key($this->stack);
    }

    /**
     * @inheritDoc
     */
    public function next()
    {
        return next($this->stack);
    }

    /**
     * @inheritDoc
     */
    public function push(ThrowableHandler $handler): HandlerStackContract
    {
        array_unshift($this->stack, $handler);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rewind()
    {
        reset($this->stack);
    }

    /**
     * @inheritDoc
     */
    public function valid()
    {
        return key($this->stack) !== null;
    }

}