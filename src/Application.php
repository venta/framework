<?php declare(strict_types = 1);

namespace Venta\Framework;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Venta\Container\Container;
use Venta\Framework\Contracts\ApplicationContract;
use Zend\Diactoros\Response;

/**
 * Class Application
 *
 * @package Venta\Framework
 */
abstract class Application extends Container implements ApplicationContract
{
    /**
     * Construct function
     */
    public function __construct()
    {
        $this->configure();
    }

    /**
     * {@inheritdoc}
     */
    abstract public function configure();

    /**
     * Function, called in order to run application.
     *
     * @param  RequestInterface $request
     * @return ResponseInterface
     */
    public function run(RequestInterface $request): ResponseInterface
    {
        $this->singleton(RequestInterface::class, $request);
        $this->singleton(ResponseInterface::class, new Response);

        $this->make(ResponseInterface::class)->getBody()->write('Hi there. I\'m Venta');

        return $this->make(ResponseInterface::class);
    }

    /**
     * Function, called in order to terminate application.
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     */
    public function terminate(RequestInterface $request, ResponseInterface $response)
    {

    }
}