<?php declare(strict_types = 1);

namespace Venta\Contracts\Config;

/**
 * Interface ConfigParserCollection
 *
 * @package Venta\Contracts\Config
 */
interface ConfigParserCollection
{
    /**
     * Add parser to collection.
     *
     * @param  string $parser
     * @return void
     */
    public function add(string $parser);

    /**
     * Returns array with all defined parsers.
     *
     * @return array
     */
    public function all(): array;
}