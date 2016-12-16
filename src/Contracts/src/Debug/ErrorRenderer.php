<?php declare(strict_types = 1);

namespace Venta\Contracts\Debug;

use Throwable;

/**
 * Interface ErrorRenderer
 *
 * @package Venta\Contracts\Debug
 */
interface ErrorRenderer
{

    /**
     * Renders throwable error.
     *
     * @param Throwable $e
     * @return mixed
     */
    public function render(Throwable $e);
}