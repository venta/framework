<?php declare(strict_types = 1);


namespace Venta\Contracts\Debug;

use Traversable;

/**
 * Interface ErrorReporterStack
 *
 * @package Venta\Contracts\Debug
 */
interface ErrorReporterStack extends Traversable
{
    /**
     * Adds error reporter class to the stack.
     *
     * @param string $reporterClass
     * @return ErrorReporterStack
     */
    public function push(string $reporterClass): ErrorReporterStack;
}