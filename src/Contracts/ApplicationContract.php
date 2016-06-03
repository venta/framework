<?php declare(strict_types = 1);

namespace Venta\Framework\Contracts;

/**
 * Interface ApplicationContract
 *
 * @package Venta\Framework\Contracts
 */
interface ApplicationContract
{
    /**
     * First function, called in application constructor
     * Is used in order to set up application, before running it.
     */
    public function configure();
}