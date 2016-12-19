<?php declare(strict_types = 1);

namespace Venta\Contracts\Debug;

use Throwable;

/**
 * Interface ErrorReporter
 *
 * @package Venta\Contracts\Debug
 */
interface ErrorReporter
{
    /**
     * Reports throwable error.
     *
     * @param Throwable $e
     * @return void
     */
    public function report(Throwable $e);
}