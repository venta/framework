<?php declare(strict_types = 1);

namespace Venta\ErrorHandler;

use Abava\Http\Factory\ResponseFactory;
use Abava\Routing\Contract\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Whoops\RunInterface;

/**
 * Class ErrorHandlerMiddleware
 *
 * @package Venta\ErrorHandler
 */
class ErrorHandlerMiddleware implements Middleware
{
    /**
     * Whoops error handler instance
     *
     * @see \Whoops\Run
     *
     * @var RunInterface
     */
    protected $run;

    /**
     * Response factory to create new Response instance
     *
     * @var ResponseFactory
     */
    protected $responseFactory;

    /**
     * ErrorHandlerMiddleware constructor.
     *
     * @param RunInterface $run
     * @param ResponseFactory $responseFactory
     */
    public function __construct(RunInterface $run, ResponseFactory $responseFactory)
    {
        $this->run = $run;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param RequestInterface $request
     * @param \Closure $next
     * @return ResponseInterface
     */
    public function handle(RequestInterface $request, \Closure $next) : ResponseInterface
    {
        try {
            return $next($request);
        } catch (\Throwable $e) {
            $this->run->allowQuit(false);
            $this->run->sendHttpCode(false);
            $this->run->writeToOutput(false);
            return $this->responseFactory
                ->createResponse($e->getCode() >= 400 ? $e->getCode() : 500)
                ->append($this->run->handleException($e));
        }
    }


}