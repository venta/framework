<?php declare(strict_types = 1);

namespace Venta\Adr;

use Venta\Contracts\Adr\Input as InputContract;
use Venta\Contracts\Http\Request;

/**
 * Class Input
 *
 * @package Venta\Adr
 */
final class Input implements InputContract
{
    /**
     * @inheritDoc
     */
    public function process(Request $request): array
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