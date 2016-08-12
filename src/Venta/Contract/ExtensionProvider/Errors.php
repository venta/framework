<?php declare(strict_types = 1);

namespace Venta\Contract\ExtensionProvider;

use Whoops\RunInterface;

/**
 * Interface Errors
 *
 * @package Venta\Contract\ExtensionProvider
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