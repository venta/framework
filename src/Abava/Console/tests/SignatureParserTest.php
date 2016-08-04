<?php

namespace Abava\Console\Tests;

use Abava\Console\Command\SignatureParser;
use PHPUnit\Framework\TestCase;

class SignatureParserTest extends TestCase
{
    /**
     * Types array to compare with
     *
     * @var array
     */
    protected $_types = [
        'required' => 1,
        'optional' => 2,
        'required_array' => 5,
        'optional_array' => 6
    ];

    /**
     * @test
     */
    public function canParseSimpleName()
    {
        $signature = new SignatureParser();
        $parsed = $signature->parse('venta');

        $this->assertInternalType('array', $parsed);
        $this->assertArrayHasKey('name', $parsed);
        $this->assertEquals('venta', $parsed['name']);
    }

    /**
     * @test
     */
    public function canParseComplexName()
    {
        $signature = new SignatureParser();
        $parsed = $signature->parse('venta:test');

        $this->assertEquals('venta:test', $parsed['name']);
    }

    /**
     * @test
     */
    public function canParseSimpleArgument()
    {
        $signature = new SignatureParser();
        $parsed = $signature->parse('venta:test {argument}');
        $argument = $parsed['arguments'][0];

        $this->assertArrayHasKey('arguments', $parsed);
        $this->assertCount(1, $parsed['arguments']);
        $this->assertInternalType('array', $parsed['arguments']);

        foreach (['name', 'type', 'default', 'description'] as $key) {
            $this->assertArrayHasKey($key, $argument);
        }

        $this->assertEquals('argument', $argument['name']);
        $this->assertEquals($this->_types['required'], $argument['type']);
        $this->assertNull($argument['default']);
        $this->assertNull($argument['description']);
    }

    /**
     * @test
     */
    public function canParseOptionalArgument()
    {
        $parsed = (new SignatureParser())->parse('venta:test {argument=}');
        $argument = $parsed['arguments'][0];

        $this->assertCount(1, $parsed['arguments']);
        $this->assertEquals('argument', $argument['name']);
        $this->assertEquals($this->_types['optional'], $argument['type']);
        $this->assertNull($argument['default']);
        $this->assertNull($argument['description']);
    }

    /**
     * @test
     */
    public function canParseOptionalArrayArgument()
    {
        $parsed = (new SignatureParser())->parse('venta:test {argument[]=}');
        $argument = $parsed['arguments'][0];

        $this->assertCount(1, $parsed['arguments']);
        $this->assertEquals('argument', $argument['name']);
        $this->assertEquals($this->_types['optional_array'], $argument['type']);
        $this->assertNull($argument['default']);
        $this->assertNull($argument['description']);
    }

    /**
     * @test
     */
    public function canParseOptionalArgumentWithDefault()
    {
        $parsed = (new SignatureParser())->parse('venta:test {argument=default value}');
        $argument = $parsed['arguments'][0];

        $this->assertCount(1, $parsed['arguments']);
        $this->assertEquals('argument', $argument['name']);
        $this->assertEquals($this->_types['optional'], $argument['type']);
        $this->assertEquals('default value', $argument['default']);
        $this->assertNull($argument['description']);
    }

    /**
     * @test
     */
    public function canParseOptionalArrayArgumentWithDefault()
    {
        $parsed = (new SignatureParser())->parse('venta:test {argument[]=default value,second default}');
        $argument = $parsed['arguments'][0];

        $this->assertCount(1, $parsed['arguments']);
        $this->assertEquals('argument', $argument['name']);
        $this->assertEquals($this->_types['optional_array'], $argument['type']);
        $this->assertNull($argument['description']);

        $this->assertInternalType('array', $argument['default']);
        $this->assertCount(2, $argument['default']);
        $this->assertEquals('second default', $argument['default'][1]);
    }

    /**
     * @test
     */
    public function canParseOptionalArrayArgumentWithDefaultAndDescription()
    {
        $parsed = (new SignatureParser())->parse('venta:test {argument[]=default value,second default:Command description goes here}');
        $argument = $parsed['arguments'][0];

        $this->assertCount(1, $parsed['arguments']);
        $this->assertEquals('argument', $argument['name']);
        $this->assertEquals($this->_types['optional_array'], $argument['type']);
        $this->assertEquals('Command description goes here', $argument['description']);

        $this->assertInternalType('array', $argument['default']);
        $this->assertCount(2, $argument['default']);
        $this->assertEquals('second default', $argument['default'][1]);
    }

    /**
     * @test
     */
    public function canParseOptions()
    {
        $parsed = (new SignatureParser())->parse('venta:test {--option[]=default value,second default:Option description goes here}');
        $option = $parsed['options'][0];

        $this->assertCount(1, $parsed['options']);
        $this->assertEquals('option', $option['name']);
        $this->assertEquals($this->_types['optional_array'], $option['type']);
        $this->assertEquals('Option description goes here', $option['description']);

        $this->assertInternalType('array', $option['default']);
        $this->assertCount(2, $option['default']);
        $this->assertEquals('second default', $option['default'][1]);
    }

    /**
     * @test
     */
    public function canParseArrayArgument()
    {
        $parsed = (new SignatureParser())->parse('venta:test {argument[]}');
        $argument = $parsed['arguments'][0];

        $this->assertCount(1, $parsed['arguments']);
        $this->assertEquals('argument', $argument['name']);
        $this->assertEquals($this->_types['required_array'], $argument['type']);
        $this->assertNull($argument['default']);
        $this->assertNull($argument['description']);
    }
}