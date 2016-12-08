<?php declare(strict_types = 1);


namespace Venta\Contracts\Debug;

use Iterator;
use IteratorAggregate;
use Traversable;

/**
 * Interface ErrorReporterStack
 *
 * @package Venta\Contracts\Debug
 */
interface ErrorReporterStack extends IteratorAggregate
{
    /**
     * @return Traversable|Iterator|ErrorReporter[]
     */
    public function getIterator();

    /**
     * Adds error reporter class to the stack.
     *
     * @param string $reporterClass
     * @return ErrorReporterStack
     */
    public function push(string $reporterClass): ErrorReporterStack;
}