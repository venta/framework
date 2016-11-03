<?php declare(strict_types = 1);

namespace Venta\Contracts\Adr;

/**
 * Interface Payload
 *
 * @package Venta\Contracts\Adr
 */
interface Payload
{

    /**
     * Generic payload statuses.
     */
    const
        OK = 'ok',
        VALIDATION_FAILED = 'validation_failed',
        EXCEPTION = 'exception',
        ERROR = 'error';

    /**
     * Return Domain handler input arguments.
     *
     * @return array
     */
    public function getInput(): array;

    /**
     * Returns Domain handling result.
     *
     * @return mixed
     */
    public function getOutput();

    /**
     * Returns Domain handling status.
     *
     * @return string
     */
    public function getStatus(): string;
}