<?php declare(strict_types = 1);

namespace Venta\Framework\ErrorHandler;

use Venta\Framework\Http\Factory\ResponseFactory;
use Venta\Http\Contract\RequestContract;
use Venta\Http\Contract\ResponseContract;
use Venta\Routing\Contract\MiddlewareContract;
use Whoops\RunInterface;

/**
 * Class ErrorHandlerMiddleware
 *
 * @package Venta\Framework\ErrorHandler
 */
class ErrorHandlerMiddleware implements MiddlewareContract
{
    /**
     * Whoops error handler instance
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
     * @param RunInterface    $run
     * @param ResponseFactory $responseFactory
     */
    public function __construct(RunInterface $run, ResponseFactory $responseFactory)
    {
        $this->run = $run;
        $this->responseFactory = $responseFactory;
    }

    /**
     * @param RequestContract $request
     * @param \Closure        $next
     * @return ResponseContract
     */
    public function handle(RequestContract $request, \Closure $next) : ResponseContract
    {
        try{
            return $next($request);
        }
        catch (\Throwable $e) {
            $this->run->allowQuit(false);
            $this->run->sendHttpCode(false);
            $this->run->writeToOutput(false);
            return $this->responseFactory
                ->new()
                ->append($this->run->handleException($e))
                ->withStatus($e->getCode() >= 400 ? $e->getCode() : 500);
        }
    }


}