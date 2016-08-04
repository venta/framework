<?php

use PHPUnit\Framework\TestCase;

/**
 * Class ContainerTest
 */
class ContainerTest extends TestCase
{
    /**
     * @var \Abava\Container\Container
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setUp()
    {
        $this->container = new Abava\Container\Container;
    }

    /**
     * @test
     */
    public function canResolveSimpleClass()
    {
        $this->assertInstanceOf(\stdClass::class, $this->container->make('\stdClass'));
        $this->assertInstanceOf(stdClass::class, $this->container->make('stdClass'));
    }

    /**
     * @test
     */
    public function makeAndGetAreTheSame()
    {
        $this->container->bind('class', new stdClass());
        $this->assertSame($this->container->make('class'), $this->container->get('class'));
    }

    /**
     * @test
     */
    public function canCheckIfIsBinded()
    {
        $this->container->bind('simple', stdClass::class);
        $this->assertTrue($this->container->has('simple'));
        $this->assertFalse($this->container->has('complex'));
    }

    /**
     * @test
     */
    public function canResolveInstance()
    {
        $this->container->bind('instance', $this);

        $this->assertSame($this, $this->container->make('instance'));
    }

    /**
     * @test
     */
    public function canResolveClassWithConstructorParameters()
    {
        $this->assertInstanceOf('SimpleConstructorParametersClass',
            $this->container->make('SimpleConstructorParametersClass'));
        $this->assertInstanceOf('stdClass', $this->container->make('SimpleConstructorParametersClass')->getItem());
    }

    /**
     * @test
     */
    public function canBindStringInstance()
    {
        $this->container->bind('\stdClass', 'stdClass');
        $this->container->bind('simple', 'stdClass');
        $this->container->singleton('complex', 'SimpleConstructorParametersClass');

        $this->assertTrue($this->container->has('complex'));
        $this->assertTrue($this->container->has('\stdClass'));
        $this->assertFalse($this->container->has('non-existing'));
        $this->assertInstanceOf('stdClass', $this->container->make('simple'));
        $this->assertInstanceOf('SimpleConstructorParametersClass', $this->container->make('complex'));
        $this->assertInstanceOf('stdClass', $this->container->make('complex')->getItem());
        $this->assertSame($this->container->make('complex'), $this->container->make('complex'));
        $this->assertNotSame($this->container->make('simple'), $this->container->make('simple'));

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Container item "simple" is already defined');
        $this->container->bind('simple', 'stdClass');
    }

    /**
     * @test
     */
    public function canBindClosure()
    {
        $this->container->bind('simple', function () {
            return new \stdClass;
        });

        $this->container->singleton('complex', function (\Abava\Container\Container $container) {
            return $container->make('SimpleConstructorParametersClass');
        });

        $this->assertTrue($this->container->has('complex'));
        $this->assertInstanceOf('stdClass', $this->container->make('simple'));
        $this->assertInstanceOf('SimpleConstructorParametersClass', $this->container->make('complex'));
        $this->assertInstanceOf('stdClass', $this->container->make('complex')->getItem());
        $this->assertSame($this->container->make('complex'), $this->container->make('complex'));
        $this->assertNotSame($this->container->make('simple'), $this->container->make('simple'));
    }

    /**
     * @test
     */
    public function canCallMethodOutOfContainer()
    {
        $this->assertInstanceOf('stdClass',
            $this->container->call('SimpleConstructorParametersClass@methodInjectTest'));

        $this->expectException(ReflectionException::class);
        $this->expectExceptionMessage('Method SimpleConstructorParametersClass::nonExistingMethod() does not exist');
        $this->assertNull($this->container->call('SimpleConstructorParametersClass@nonExistingMethod'));
    }

    /**
     * @test
     */
    public function canCallClosureOutOfContainer()
    {
        $this->assertInstanceOf('stdClass', $this->container->call(function (\stdClass $item) {
            return $item;
        }));
    }

    /**
     * @test
     */
    public function wontCallAnythingElseExceptClosureAndString()
    {
        $this->expectException('InvalidArgumentException');
        $this->expectExceptionMessage('"42" can not be called out of container');

        $this->container->call(42);
    }

    /**
     * @test
     */
    public function canResolveWithManualArguments()
    {
        $stub = new class extends \stdClass
        {
        };
        $resolved = $this->container->make('SimpleConstructorParametersClass', ['item' => $stub]);

        $this->assertInstanceOf('stdClass', $resolved->getItem());
        $this->assertEquals(0, $resolved->getInteger());
        $this->assertSame($stub, $resolved->getItem());
        $this->assertSame($stub,
            $this->container->call('SimpleConstructorParametersClass@methodInjectTest', ['item' => $stub]));
    }
}

class SimpleConstructorParametersClass
{
    protected $item;
    protected $integer;
    
    public function __construct(\stdClass $item, int $integer = 0)
    {
        $this->item = $item;
        $this->integer = $integer;
    }

    public function getItem()
    {
        return $this->item;
    }

    public function getInteger()
    {
        return $this->integer;
    }

    public function methodInjectTest(\stdClass $item)
    {
        return $item;
    }
}

class RewriteTestClass extends \stdClass
{
    protected $value;

    public function setValue($value)
    {
        $this->value = $value;
    }

    public function getValue()
    {
        return $this->value;
    }
}