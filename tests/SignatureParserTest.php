<?php

namespace Abava\Console\Tests;

use Abava\Console\Command\SignatureParser;

class SignatureParserTest extends \PHPUnit_Framework_TestCase
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

    public function testCanParseSimpleName()
    {
        $signature = new SignatureParser();
        $parsed = $signature->parse('venta');

        $this->assertInternalType('array', $parsed);
        $this->assertArrayHasKey('name', $parsed);
        $this->assertEquals('venta', $parsed['name']);
    }

    public function testCanParseComplexName()
    {
        $signature = new SignatureParser();
        $parsed = $signature->parse('venta:test');

        $this->assertEquals('venta:test', $parsed['name']);
    }

    public function testCanParseSimpleArgument()
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

    public function testCanParseOptionalArgument()
    {
        $parsed = (new SignatureParser())->parse('venta:test {argument=}');
        $argument = $parsed['arguments'][0];

        $this->assertCount(1, $parsed['arguments']);
        $this->assertEquals('argument', $argument['name']);
        $this->assertEquals($this->_types['optional'], $argument['type']);
        $this->assertNull($argument['default']);
        $this->assertNull($argument['description']);
    }

    public function testCanParseOptionalArrayArgument()
    {
        $parsed = (new SignatureParser())->parse('venta:test {argument[]=}');
        $argument = $parsed['arguments'][0];

        $this->assertCount(1, $parsed['arguments']);
        $this->assertEquals('argument', $argument['name']);
        $this->assertEquals($this->_types['optional_array'], $argument['type']);
        $this->assertNull($argument['default']);
        $this->assertNull($argument['description']);
    }

    public function testCanParseOptionalArgumentWithDefault()
    {
        $parsed = (new SignatureParser())->parse('venta:test {argument=default value}');
        $argument = $parsed['arguments'][0];

        $this->assertCount(1, $parsed['arguments']);
        $this->assertEquals('argument', $argument['name']);
        $this->assertEquals($this->_types['optional'], $argument['type']);
        $this->assertEquals('default value', $argument['default']);
        $this->assertNull($argument['description']);
    }

    public function testCanParseOptionalArrayArgumentWithDefault()
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

    public function testCanParseOptionalArrayArgumentWithDefaultAndDescription()
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

    public function testCanParseOptions()
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

    public function testCanParseArrayArgument()
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