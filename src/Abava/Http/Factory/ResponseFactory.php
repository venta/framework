<?php declare(strict_types = 1);

namespace Abava\Http\Factory;

use Abava\Http\Contract\Response as ResponseContract;
use Abava\Http\Response;
use Psr\Http\Message\StreamInterface;

/**
 * Class ResponseFactory
 *
 * @package Abava\Framework\Http\Factory
 */
class ResponseFactory
{
    /**
     * Creates new Response instance
     *
     * @param int $code
     * @param array $headers
     * @param StreamInterface|null $stream
     * @return ResponseContract
     */
    public function createResponse($code = 200, array $headers = [], StreamInterface $stream = null) : ResponseContract
    {
        return new Response($stream ?: 'php://memory', $code, $headers);
    }

    /**
     * Returns new Response Instance w/o any arguments
     *
     * @return Response
     */
    public function new(): Response
    {
        return $this->createResponse();
    }

    /**
     * Helper function for redirect response
     *
     * @param string $url
     * @param int $status
     * @return ResponseContract
     */
    public function redirect(string $url, int $status = 302): ResponseContract
    {
        return $this->createResponse($status, ['location' => $url]);
    }

    // todo Add helper methods, e.g. RedirectResponse, JsonResponse, etc.

}