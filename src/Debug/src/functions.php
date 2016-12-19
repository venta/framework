<?php

use Venta\Debug\VarDumper;

if (!function_exists('dd')) {

    /**
     * Dumps the provided variable and stops the script execution.
     *
     * @param mixed ...$args
     */
    function dd(...$args)
    {
        array_walk($args, [VarDumper::class, 'dump']);
        die(1);
    }
}