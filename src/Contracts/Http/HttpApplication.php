<?php declare(strict_types = 1);

namespace Venta\Contracts\Http;

/**
 * Interface HttpApplication
 *
 * @package Venta\Contracts\Http
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