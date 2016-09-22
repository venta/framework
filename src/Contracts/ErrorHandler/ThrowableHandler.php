<?php declare(strict_types = 1);

namespace Venta\Contracts\ErrorHandler;

use Throwable;

/**
 * Interface ThrowableHandler
 * Handles provided instance of Throwable. May be combined with other handlers in stack.
 *
 * @package Venta\Contracts\ErrorHandler
 */
interface ThrowableHandler
{

    /**
     * Handles exception or error
     *
     * @param Throwable $throwable
     * @return void
     */
    public function handle(Throwable $throwable);

}