<?php declare(strict_types = 1);

namespace Venta\Adr;

use Psr\Http\Message\ServerRequestInterface;
use Venta\Contracts\Adr\Input as InputContract;

/**
 * Class Input
 *
 * @package Venta\Adr
 */
class Input implements InputContract
{
    /**
     * @inheritDoc
     */
    public function process(ServerRequestInterface $request): array
    {
        return [
            array_replace(
                (array)$request->getQueryParams(),
                (array)$request->getParsedBody(),
                (array)$request->getUploadedFiles(),
                (array)$request->getCookieParams(),
                (array)$request->getAttributes()
            ),
        ];
    }
}