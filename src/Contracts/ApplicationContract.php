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
     * Returns environment name application is running in
     *
     * @return string
     */
    public function environment(): string;

    /**
     * Defines, if application is in local environment
     *
     * @return bool
     */
    public function isLocalEnvironment(): bool;

    /**
     * Defines, if application is in stage environment
     *
     * @return bool
     */
    public function isStageEnvironment(): bool;

    /**
     * Defines, if application is in live environment
     *
     * @return bool
     */
    public function isLiveEnvironment(): bool;

    /**
     * Defines, if application is in local environment
     *
     * @return bool
     */
    public function isTestEnvironment(): bool;

    /**
     * First function, called in application constructor
     * Is used in order to set up application, before running it.
     */
    public function configure();

    /**
     * Function, called in order to run application.
     *
     * @param  RequestInterface $request
     * @return ResponseInterface
     */
    public function run(RequestInterface $request): ResponseInterface;

    /**
     * Emits response to client
     * Sends status code, headers, echoes body
     *
     * @param ResponseInterface $response
     */
    public function emit(ResponseInterface $response);

    /**
     * Function, called in order to terminate application.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     */
    public function terminate(RequestInterface $request, ResponseInterface $response);
}