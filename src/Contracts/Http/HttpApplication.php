<?php declare(strict_types = 1);

namespace Venta\Contracts\Http;

use Psr\Http\Message\ServerRequestInterface;

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
     * @param ServerRequestInterface $request
     */
    public function run(ServerRequestInterface $request);

}