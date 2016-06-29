<?php declare(strict_types = 1);

namespace Venta\Framework\Kernel;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Venta\Framework\Contracts\ApplicationContract;
use Venta\Framework\Contracts\Kernel\HttpKernelContract;

/**
 * Class HttpKernel
 *
 * @package Venta\Framework
 */
class HttpKernel implements HttpKernelContract
{
    /**
     * Application instance holder
     *
     * @var ApplicationContract
     */
    protected $application;

    /**
     * {@inheritdoc}
     */
    public function __construct(ApplicationContract $application)
    {
        $this->application = $application;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(RequestInterface $request): ResponseInterface
    {
        return $this->application->run($request);
    }

    /**
     * {@inheritdoc}
     */
    public function terminate(RequestInterface $request, ResponseInterface $response)
    {
        $this->application->terminate($request, $response);
    }
}