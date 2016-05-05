<?php declare(strict_types = 1);

namespace Venta\Framework;

use Venta\Container\Container;

/**
 * Class Skeleton
 *
 * @package Venta\Framework
 */
abstract class Skeleton extends Container
{
    /**
     * Version holder
     *
     * @var string
     */
    protected $_version = '0.0.1';

    /**
     * Container holder
     *
     * @var ContainerContract
     */
    protected $_container;

    /**
     * Construct function
     */
    public function __construct()
    {
        $this->configure();
    }

    /**
     * Returns version
     *
     * @return string
     */
    public function version(): string
    {
        return $this->_version;
    }

    /**
     * Configure application
     */
    abstract function configure();
}