<?php declare(strict_types = 1);

namespace Venta\Framework\Http\Factory;

use Psr\Http\Message\StreamInterface;
use Venta\Http\Contract\ResponseContract;
use Venta\Http\Response;

/**
 * Class ResponseFactory
 * @package Venta\Framework\Http\Factory
 */
class ResponseFactory
{

    /**
     * Response classname to use
     *
     * @var string
     */
    protected $responseClass;

    /**
     * ResponseFactory constructor.

     * @param string $responseContractImplementingClassname
     */
    public function __construct(string $responseContractImplementingClassname = Response::class)
    {
        if (!is_subclass_of($responseContractImplementingClassname, ResponseContract::class, true)) {
            throw new \InvalidArgumentException('Provided classname must implement Venta\Http\Contract\ResponseContract');
        }
        $this->responseClass = $responseContractImplementingClassname;
    }

    /**
     * Creates new Response instance
     *
     * @param StreamInterface|null $stream
     * @param int                  $status
     * @param array                $headers
     * @return ResponseContract
     */
    public function make(StreamInterface $stream = null, int $status = 200, array $headers = []): ResponseContract
    {
        return new $this->responseClass($stream ?: 'php://memory', $status, $headers);
    }

    /**
     * Returns new Response Instance w/o any arguments
     *
     * @return ResponseContract
     */
    public function new(): ResponseContract
    {
        return new $this->responseClass;
    }

    /**
     * Helper function for redirect response
     *
     * @param string $url
     * @param int    $status
     * @return ResponseContract
     */
    public function redirect(string $url, int $status = 302): ResponseContract
    {
        return $this->make(null, $status, ['location' => $url]);
    }

    // todo Add helper methods, e.g. RedirectResponse, JsonResponse, etc.

}