<?php declare(strict_types = 1);

namespace Venta\Contracts\ExtensionProvider;

use Whoops\RunInterface;

/**
 * Interface Errors
 *
 * @package Venta\Contracts\ExtensionProvider
 */
interface Errors
{

    /**
     * Add error handler to handle exceptions
     * @see Whoops\Handler\HandlerInterface
     *
     * @param RunInterface $run
     * @return void
     */
    public function errors(RunInterface $run);

}