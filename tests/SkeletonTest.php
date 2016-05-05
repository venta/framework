<?php

/**
 * Class SkeletonTest
 */
class SkeletonTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function canDisplayVersion()
    {
        $this->assertTrue(is_string($this->_getSkeletonInstance()->version()));
    }

    /**
     * Returns skeleton instance
     *
     * @return \Venta\Framework\Skeleton
     */
    protected function _getSkeletonInstance()
    {
        return new class extends \Venta\Framework\Skeleton {
            public function configure() {}
        };
    }
}