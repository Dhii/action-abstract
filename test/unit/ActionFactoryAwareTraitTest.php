<?php

namespace Dhii\Action\UnitTest;

use Dhii\Action\ActionFactoryAwareTrait as TestSubject;
use Dhii\Action\ActionFactoryInterface;
use InvalidArgumentException;
use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class ActionFactoryAwareTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\Action\ActionFactoryAwareTrait';

    /**
     * The class name of an action factory.
     *
     * @since [*next-version*]
     */
    const CLASSNAME_ACTION_FACTORY = 'Dhii\Action\ActionFactoryInterface';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
     *
     * @return MockObject The new instance.
     */
    public function createInstance($methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
            '__',
        ]);

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
            ->setMethods($methods)
            ->getMockForTrait();

        $mock->method('__')
            ->will($this->returnArgument(0));

        return $mock;
    }

    /**
     * Merges the values of two arrays.
     *
     * The resulting product will be a numeric array where the values of both inputs are present, without duplicates.
     *
     * @since [*next-version*]
     *
     * @param array $destination The base array.
     * @param array $source      The array with more keys.
     *
     * @return array The array which contains unique values
     */
    public function mergeValues($destination, $source)
    {
        return array_keys(array_merge(array_flip($destination), array_flip($source)));
    }

    /**
     * Creates a mock that both extends a class and implements interfaces.
     *
     * This is particularly useful for cases where the mock is based on an
     * internal class, such as in the case with exceptions. Helps to avoid
     * writing hard-coded stubs.
     *
     * @since [*next-version*]
     *
     * @param string   $className      Name of the class for the mock to extend.
     * @param string[] $interfaceNames Names of the interfaces for the mock to implement.
     *
     * @return MockBuilder The builder for a mock of an object that extends and implements
     *                     the specified class and interfaces.
     */
    public function mockClassAndInterfaces($className, $interfaceNames = [])
    {
        $paddingClassName = uniqid($className);
        $definition = vsprintf('abstract class %1$s extends %2$s implements %3$s {}', [
            $paddingClassName,
            $className,
            implode(', ', $interfaceNames),
        ]);
        eval($definition);

        return $this->getMockBuilder($paddingClassName);
    }

    /**
     * Creates a mock that uses traits.
     *
     * This is particularly useful for testing integration between multiple traits.
     *
     * @since [*next-version*]
     *
     * @param string[] $traitNames Names of the traits for the mock to use.
     *
     * @return MockBuilder The builder for a mock of an object that uses the traits.
     */
    public function mockTraits($traitNames = [])
    {
        $paddingClassName = uniqid('Traits');
        $definition = vsprintf('abstract class %1$s {%2$s}', [
            $paddingClassName,
            implode(
                ' ',
                array_map(
                    function ($v) {
                        return vsprintf('use %1$s;', [$v]);
                    },
                    $traitNames)),
        ]);
        var_dump($definition);
        eval($definition);

        return $this->getMockBuilder($paddingClassName);
    }

    /**
     * Creates a new exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return RootException|MockObject The new exception.
     */
    public function createException($message = '')
    {
        $mock = $this->getMockBuilder('Exception')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new Invalid Argument exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return InvalidArgumentException|MockObject The new exception.
     */
    public function createInvalidArgumentException($message = '')
    {
        $mock = $this->getMockBuilder('InvalidArgumentException')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a new mock of an action factory.
     *
     * @since [*next-version*]
     *
     * @param array|null $methods The methods to mock.
     *
     * @return MockObject|ActionFactoryInterface The new action factory mock.
     */
    public function createActionFactory($methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
            'make',
        ]);

        $mock = $this->getMockBuilder(static::CLASSNAME_ACTION_FACTORY)
            ->setMethods($methods)
            ->getMock();

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType(
            'object',
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests that `_setActionFactory()` works as expected when given a `null` action.
     *
     * @since [*next-version*]
     */
    public function testSetActionFactoryNull()
    {
        $actionFactory = null;
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $_subject->actionFactory = uniqid('actionFactory');

        $subject->expects($this->exactly(0))
            ->method('_normalizeActionFactory');

        $_subject->_setActionFactory($actionFactory);
        $this->assertEquals($actionFactory, $_subject->actionFactory);
    }

    /**
     * Tests that `_setActionFactory()` works as expected when given a valid action.
     *
     * @since [*next-version*]
     */
    public function testSetActionFactoryNotNullSuccess()
    {
        $actionFactory = $this->createActionFactory();
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $_subject->actionFactory = null;

        $subject->expects($this->exactly(1))
            ->method('_normalizeActionFactory')
            ->with($actionFactory)
            ->will($this->returnValue($actionFactory));

        $_subject->_setActionFactory($actionFactory);
        $this->assertEquals($actionFactory, $_subject->actionFactory);
    }

    /**
     * Tests that `_setActionFactory()` works as expected when given an invalid action.
     *
     * @since [*next-version*]
     */
    public function testSetActionFactoryNotNullFailure()
    {
        $actionFactory = uniqid('action');
        $exception = $this->createInvalidArgumentException('Invalid action');
        $subject = $this->createInstance();
        $_subject = $this->reflect($subject);

        $_subject->actionFactory = null;

        $subject->expects($this->exactly(1))
            ->method('_normalizeActionFactory')
            ->with($actionFactory)
            ->will($this->throwException($exception));

        $this->setExpectedException('InvalidArgumentException');
        $_subject->_setActionFactory($actionFactory);
    }
}
