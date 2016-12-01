<?php declare(strict_types = 1);

namespace Venta\Contracts\ErrorHandler;

use Iterator;

/**
 * Interface HandlerStack
 * Contains stack of ThrowableHandler, can be iterated by foreach loop.
 *
 * @package Venta\Contracts\ErrorHandler
 */
interface HandlerStack extends Iterator
{

    /**
     * Adds new handler to the begging of stack.
     *
     * @param ThrowableHandler $handler
     * @return HandlerStack
     */
    public function push(ThrowableHandler $handler): HandlerStack;

}