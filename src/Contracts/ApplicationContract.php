<?php declare(strict_types = 1);

namespace Venta\Framework\Contracts;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Interface ApplicationContract
 *
 * @package Venta\Framework\Contracts
 */
interface ApplicationContract
{
    /**
     * Returns application version string
     *
     * @return string
     */
    public function version(): string;

    /**
     * Defines, if application is running in CLI
     *
     * @return bool
     */
    public function isCli(): bool;

    /**
     * First function, called in application constructor
     * Is used in order to set up application, before running it.
     */
    public function configure();

    /**
     * Function, called in order to terminate application.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     */
    public function terminate(RequestInterface $request, ResponseInterface $response);
}