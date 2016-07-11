<?php declare(strict_types = 1);

namespace Venta\Framework\Contracts\ExtensionProvider;

use Whoops\RunInterface;

/**
 * Interface ErrorsContract
 *
 * @package Venta\Framework\Contracts\ExtensionProvider
 */
interface ErrorsContract
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