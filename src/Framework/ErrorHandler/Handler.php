<?php declare(strict_types = 1);

namespace Venta\ErrorHandler;

use Whoops\Exception\Inspector;
use Whoops\Handler\PrettyPageHandler;

/**
 * Class Handler
 *
 * @package Venta\ErrorHandler
 */
class Handler
{

    public function handle(\Throwable $throwable)
    {
        // echo '1';
        $pretty = new PrettyPageHandler();
        $pretty->setException($throwable);
        $pretty->setInspector(new Inspector($throwable));
        $pretty->handle();
    }

}