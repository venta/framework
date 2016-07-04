<?php declare(strict_types = 1);

namespace Venta\Framework\Http\Factory;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Venta\Framework\Http\Response;

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
     *
     * @param string $responseInterfaceImplementingClassname
     */
    public function __construct(string $responseInterfaceImplementingClassname = Response::class)
    {
        if (!is_subclass_of($responseInterfaceImplementingClassname, ResponseInterface::class, true)) {
            throw new \InvalidArgumentException('Provided classname must implement Psr\Http\Message\ResponseInterface');
        }
        $this->responseClass = $responseInterfaceImplementingClassname;
    }

    /**
     * Creates new Response instance
     *
     * @param StreamInterface|null $stream
     * @param int                  $status
     * @param array                $headers
     * @return ResponseInterface|Response
     */
    public function make(StreamInterface $stream = null, int $status = 200, array $headers = []): ResponseInterface
    {
        return new $this->responseClass($stream ?: 'php://memory', $status, $headers);
    }

    /**
     * Returns new Response Instance w/o any arguments
     *
     * @return ResponseInterface|Response
     */
    public function new(): ResponseInterface
    {
        return new $this->responseClass;
    }

    /**
     * Helper function for redirect response
     *
     * @param string $url
     * @param int    $status
     * @return ResponseInterface
     */
    public function redirect(string $url, int $status = 302): ResponseInterface
    {
        return $this->make(null, $status, ['location' => $url]);
    }

    // todo Add helper methods, e.g. RedirectResponse, JsonResponse, etc.

}