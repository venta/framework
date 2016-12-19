<?php declare(strict_types = 1);

namespace Venta\Debug;

use IteratorAggregate;
use Throwable;
use Venta\Contracts\Container\Container;
use Venta\Contracts\Debug\ErrorReporter;
use Venta\Contracts\Debug\ErrorReporterAggregate as ErrorReporterStackContract;

/**
 * Class ErrorReporterStack
 *
 * @package Venta\Debug
 */
final class ErrorReporterAggregate implements IteratorAggregate, ErrorReporterStackContract
{
    /**
     * @var Container
     */
    private $container;

    /**
     * @var string[]
     */
    private $reporterClasses = [];

    /**
     * ErrorReporterStack constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        foreach (array_unique($this->reporterClasses) as $reporter) {
            yield $this->container->get($reporter);
        }
    }

    /**
     * @inheritDoc
     */
    public function push(string $reporterClass): ErrorReporterStackContract
    {
        array_unshift($this->reporterClasses, $reporterClass);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function report(Throwable $e)
    {
        /** @var ErrorReporter $reporter */
        foreach ($this->getIterator() as $reporter) {
            $reporter->report($e);
        }
    }

}