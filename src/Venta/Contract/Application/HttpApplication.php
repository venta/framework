<?php declare(strict_types = 1);

namespace Venta\Contract\Application;

/**
 * Interface HttpApplication
 *
 * @package Venta\Contract
 */
interface HttpApplication
{

    /**
     * Runs HTTP Application
     *
     * @return void
     */
    public function run();

}