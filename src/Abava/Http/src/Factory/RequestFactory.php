<?php declare(strict_types = 1);

namespace Abava\Http\Factory;

use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\ServerRequestFactory;

/**
 * Class RequestFactory
 */
class RequestFactory extends ServerRequestFactory
{
    /**
     * todo: remove if \Zend\Diactoros\ServerRequestFactory implements PSR-16
     * Create a new server request from PHP globals.
     *
     * @return ServerRequestInterface
     */
    public function createServerRequestFromGlobals(): ServerRequestInterface
    {
        return parent::fromGlobals();
    }

}