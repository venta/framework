<?php

/**
 * Class ApplicationTest
 */
class ApplicationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Returns application instance
     *
     * @return \Venta\Framework\Application
     */
    protected function _getApplication()
    {
        return new class(__DIR__) extends \Venta\Framework\Application {};
    }
}