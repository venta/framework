<?php declare(strict_types = 1);


namespace Venta\Contracts\Debug;

use Traversable;

/**
 * Interface ErrorReporterAggregate
 *
 * @package Venta\Contracts\Debug
 */
interface ErrorReporterAggregate extends ErrorReporter, Traversable
{
    /**
     * Adds error reporter class to the stack.
     *
     * @param string $reporterClass
     * @return ErrorReporterAggregate
     */
    public function push(string $reporterClass): ErrorReporterAggregate;
}