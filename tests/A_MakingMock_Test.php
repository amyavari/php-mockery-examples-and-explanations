<?php

declare(strict_types=1);

namespace Tests;

use App\DependencyOne;
use App\DependencyTwo;
use App\Main;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * This test class explains the `Creating Test Doubles` section.
 *
 * Official documentation: https://docs.mockery.io/en/stable/reference/creating_test_doubles.html
 */
class A_MakingMock_Test extends TestCase
{
    public function test_create_full_mock_with_default_behavior(): void
    {
        /*
        | In the first option, we can create a simple full mock object for our dependency(s).
        | Using `Mockery::mock()` will create a full mock object.
        |
        | A full mock means all methods of our dependency MUST be defined.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        /*
        | `DependencyOne` has four methods, and since it's a full mock, we must define
        | expectations for ALL of them.
        | If you comment out one of these lines, Mockery will throw a BadMethodCallException.
        |
        | Note: Defining expectations will be explained in later files.
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->andReturn(10);
        $mockedDependencyOne->shouldReceive('getOneHundred')->andReturn(20);
        $mockedDependencyOne->shouldReceive('setNameProperty')->andReturn('Someone else');
        $mockedDependencyOne->shouldReceive('getNameProperty')->andReturn('Yavari');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        dump($output); // The output of calling `DependencyOne` methods is as above!

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_create_full_mock_without_breaking_test(): void
    {
        /*
        | As mentioned in the previous test, if you don't define any expectations for
        | methods that have been called, Mockery will throw a BadMethodCallException.
        |
        | To simplify tests, we can chain the `shouldIgnoreMissing()` method to prevent
        | tests from breaking for undefined methods.
        | In this case, undefined methods will return `null`, `0`, `false`, `''`, or `[]` based on their return type.
        | Please check the returned values here: https://docs.mockery.io/en/stable/reference/creating_test_doubles.html#behavior-modifiers
        |
        | If your code includes validations for their output, such as `!empty()` or `!is_null()`, it may fail.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class)->shouldIgnoreMissing();

        /*
        | If you comment out one of these lines, Mockery will return `null`, `0`, `false`, `''`, or `[]` for their output.
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->andReturn(10);
        $mockedDependencyOne->shouldReceive('getOneHundred')->andReturn(20);
        $mockedDependencyOne->shouldReceive('setNameProperty')->andReturn('Someone else');
        $mockedDependencyOne->shouldReceive('getNameProperty')->andReturn('Yavari');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_create_full_mock_without_breaking_test_and_conditions(): void
    {
        /*
        | As mentioned in the previous test, if you don't define an expectation for a method
        | and use the `shouldIgnoreMissing()` method, the return value of that method will be `null`, `''`.
        | This will break your conditions like `!empty()`, `!is_null()`, and so on.
        |
        | To avoid this, we can chain the `asUndefined()` method. Mockery will then return an object
        | from the `Mockery\Undefined` class. This object is empty but passes those conditions.
        */

        /*
        | Note: Use `asUndefined()` with caution if the methods have defined return types.
        | Returning a `Mockery\Undefined` object may conflict with the method's return type (e.g., `int`, `string`, `bool`),
        | resulting in a `TypeError`.
        |
        | This behavior is safe for methods without return types.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class)->shouldIgnoreMissing()->asUndefined();

        /*
        | If you comment out the expectation for the `getPassedNumber()` method, Mockery will return an object from the `Mockery\Undefined` class.
        | If you comment out the expectation for the `getOneHundred()` method, a TypeError exception will be thrown.
        | If you comment out other expectations, a casted string form of the `Mockery\Undefined` object will be returned.
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->andReturn(10);
        $mockedDependencyOne->shouldReceive('getOneHundred')->andReturn(20);
        $mockedDependencyOne->shouldReceive('setNameProperty')->andReturn('Someone else');
        $mockedDependencyOne->shouldReceive('getNameProperty')->andReturn('Yavari');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_create_partial_mock(): void
    {
        /*
        | In the second option, we can create a simple partial mock object for our dependency(s).
        | Using `Mockery::mock()->makePartial()` will create a partial mock object.
        |
        | A partial mock means we can define expectations for any method we want, and
        | other methods will be executed normally.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class)->makePartial();

        /*
        | `DependencyOne` has four methods, and we can define expectations for all four,
        | or only for the methods we want to.
        | If you comment out any of these lines, the method will be executed normally.
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->andReturn(10);
        $mockedDependencyOne->shouldReceive('getOneHundred')->andReturn(20);
        $mockedDependencyOne->shouldReceive('setNameProperty')->andReturn('Someone else');
        $mockedDependencyOne->shouldReceive('getNameProperty')->andReturn('Yavari');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_full_mock_with_partial_behavior(): void
    {
        /*
        | As an alternative to partial mock: with full mock objects, you can make specific methods execute normally.
        | Chain `passthru()` on their method call expectations (covered later) to achieve this.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        /*
        | If you remove `passthru()` from any of these, the method won't execute normally and will return:
        | `null`, `0`, `false`, `''`, or `[]` based on return type.
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->passthru();
        $mockedDependencyOne->shouldReceive('getOneHundred')->passthru();
        $mockedDependencyOne->shouldReceive('setNameProperty')->passthru();
        $mockedDependencyOne->shouldReceive('getNameProperty')->passthru();

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_create_one_line_mock(): void
    {
        /*
        | For all of the above options, if you have only one expectation, you can create a one-line mock!
        | Just chain everything together and, at the end, chain the `getMock()` method.
        |
        | Each one is the same as what was explained in the previous tests. To test each one, simply comment out the other three.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class)->makePartial()->shouldReceive('getOneHundred')->andReturn(10)->getMock();
        $mockedDependencyOne = Mockery::mock(DependencyOne::class)->shouldReceive('getOneHundred')->andReturn(10)->getMock();
        $mockedDependencyOne = Mockery::mock(DependencyOne::class)->shouldIgnoreMissing()->shouldReceive('getOneHundred')->andReturn(10)->getMock();
        $mockedDependencyOne = Mockery::mock(DependencyOne::class)->shouldIgnoreMissing()->asUndefined()->shouldReceive('getOneHundred')->andReturn(20)->getMock();

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_create_spy(): void
    {
        /*
        | In the third option, we can create a spy for our dependency(s).
        | Using `Mockery::spy()` will create a spy object.
        |
        | A spy means we don't need to set expectations for method calls;
        | we only want to know whether a method is called and with which argument(s).
        */
        $spyDependencyOne = Mockery::spy(DependencyOne::class);

        $main = new Main($spyDependencyOne, new DependencyTwo);
        $output = $main->run();

        /*
        | As you can see, running methods comes first, and afterward, we check what happened.
        |
        | Note: When you create a spy, methods won't work normally, and we can't define expectations like in a mock;
        | they ALWAYS return `null`, `0`, `false`, `''`, or `[]` based on their return type.
        | Please check the returned values here: https://docs.mockery.io/en/stable/reference/creating_test_doubles.html#behavior-modifiers
        |
        | If your code includes validations for their output, such as `!empty()` or `!is_null()`, it will fail.
        |
        | If you change the expected argument or add another expected call for the following methods,
        | Mockery will throw an exception to indicate that your expectation was not met.
        */
        $spyDependencyOne->shouldHaveReceived('getPassedNumber', [123]);
        $spyDependencyOne->shouldHaveReceived('getOneHundred');
        $spyDependencyOne->shouldHaveReceived('setNameProperty', ['Ali']);
        $spyDependencyOne->shouldHaveReceived('getNameProperty');

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_create_mock_for_final_classes_and_methods(): void
    {
        /*
        | Note: You can bypass mock restrictions (mentioned below)
        |       using this package: https://github.com/nunomaduro/mock-final-classes
        */

        /*
        | As Mockery states in its official documentation, it creates a new class
        | and extends our dependency class/interface/abstract class to create a mock object from it.
        | This new class will override methods based on our expectations, so:
        | - For final classes, it cannot extend that class.
        | - For final methods, it cannot override that method.
        |
        | The solution is to create and pass an object of our dependency to Mockery.
        | The problem: in this case, Mockery will proxy that object, so PHP type-hint
        | will throw a TypeError exception if we use it.
        */
        $mockDependencyOne = Mockery::mock(new DependencyOne);

        /*
        | Since the `Main` class has a type-hint for its constructor,
        | if you mark the `DependencyOne` class as final,
        | the `Main` class will throw a TypeError exception.
        */
        $main = new Main($mockDependencyOne, new DependencyTwo);

        $this->assertInstanceOf(Main::class, $main);
    }
}
