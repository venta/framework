<?php declare(strict_types = 1);

namespace Venta\Debug;

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Dumper\HtmlDumper;

/**
 * Class VarDumper
 *
 * @package Venta\Debug
 */
final class VarDumper
{

    /**
     * @inheritDoc
     */
    public static function dump($variable)
    {
        $dumper = 'cli' === PHP_SAPI ? new CliDumper : new HtmlDumper;
        $dumper->dump((new VarCloner)->cloneVar($variable));
    }
}