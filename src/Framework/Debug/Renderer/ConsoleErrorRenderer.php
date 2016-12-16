<?php declare(strict_types = 1);

namespace Venta\Framework\Debug\Renderer;

use Error;
use ErrorException;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Output\ConsoleOutput;
use Throwable;
use Venta\Contracts\Debug\ErrorRenderer;

/**
 * Class ConsoleErrorRenderer
 *
 * @package Venta\Debug\Renderer
 */
final class ConsoleErrorRenderer implements ErrorRenderer
{

    /**
     * @inheritDoc
     */
    public function render(Throwable $e)
    {
        if ($e instanceof Error) {
            $e = new ErrorException(
                $e->getMessage(), 0, $e->getCode(), $e->getFile(), $e->getLine(), $e->getPrevious()
            );
        }

        (new Application())->renderException($e, new ConsoleOutput());
    }

}