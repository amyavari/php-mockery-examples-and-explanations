<?php

declare(strict_types=1);

namespace Tests;

use App\DependencyOne;
use App\DependencyTwo;
use App\Main;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * This test class explains the `Expectation Declarations` section.
 *  - Declaring Method Call Expectations
 *  - Declaring Method Argument Expectation
 *
 * Official documentation: https://docs.mockery.io/en/stable/reference/expectations.html
 */
class B_Expectations_PartOneTest extends TestCase
{
    public function test_define_expectation_for_one_method_call(): void
    {
        /*
        | The simplest part of defining mock expectations is specifying which methods
        | should be called. We can then define their behavior.
        |
        | The steps are straightforward: call `shouldReceive()` on the mock object.
        */

        /*
        | These lines simply declare which methods we expect to be called.
        |
        | Without additional expectations (which we'll cover later), these methods will return:
        | `null`, `0`, `false`, `''`, or `[]` based on their return type.
        | Please check the returned values here: https://docs.mockery.io/en/stable/reference/creating_test_doubles.html#behavior-modifiers
        |
        | If you comment out one of these lines, Mockery will throw a BadMethodCallException.
        |
        | Note: If we use partial mock and don't define method expectation,
        |       we won't get BadMethodCallException and methods will execute normally.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        $mockedDependencyOne->shouldReceive('getPassedNumber');
        $mockedDependencyOne->shouldReceive('getOneHundred');
        $mockedDependencyOne->shouldReceive('setNameProperty');
        $mockedDependencyOne->shouldReceive('getNameProperty');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        var_dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_define_expectation_for_multiple_method_call(): void
    {
        /*
        | To define method calls for multiple methods with same expectations,
        | or when no extra expectations are needed, we can pass them all
        | to `shouldReceive()` at once.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        $mockedDependencyOne->shouldReceive('getPassedNumber', 'getOneHundred', 'setNameProperty');
        $mockedDependencyOne->shouldReceive('getNameProperty');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        var_dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_defining_expectation_for_method_argument_simple_validation(): void
    {
        /*
        | The first expectation we typically define is which arguments the method should receive.
        | This ensures the method inside mocked class is called with correct arguments.
        |
        | To define argument expectation, chain `with()` after `shouldReceive()`.
        | We also have:
        | - `withAnyArgs()` that says everything is allowed
        | - `withNoArgs()` that says no argument should be passed
        |
        | Note: `with*()` methods act as test assertions.
        |       Since the methods inside mocked class won't be executed normally, these methods
        |       verify our code under test calls them correctly. Their actual behavior is tested
        |       in their own test suites.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);
        $mockedDependencyTwo = Mockery::mock(DependencyTwo::class);

        /*
        | In the `run()` method of `Main` class we expect:
        | - `DependencyOne->getOneHundred()` and `getNameProperty()` to be called with no arguments
        | - `DependencyOne->getPassedNumber()` to be called with `123`
        | - `DependencyOne->setNameProperty()` to be called with any argument
        |
        | Note: Define method arguments in `with()` exactly as they're called. See DependencyTwo->setPerson().
        |
        | If you change expectation here or method arguments in `Main` class,
        | Mockery will throw NoMatchingExpectationException.
        */
        $mockedDependencyOne->shouldReceive('getOneHundred', 'getNameProperty')->withNoArgs();
        $mockedDependencyOne->shouldReceive('getPassedNumber')->with(123);
        $mockedDependencyOne->shouldReceive('setNameProperty')->withAnyArgs();

        $mockedDependencyTwo->shouldReceive('getTwoHundred')->withNoArgs();
        $mockedDependencyTwo->shouldReceive('setPerson')->with('Ali', 'Yavari', 34);

        $main = new Main($mockedDependencyOne, $mockedDependencyTwo);
        $output = $main->run();

        var_dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_defining_expectation_for_method_argument_with_closure(): void
    {
        /*
        | For dynamic argument validation, use `withArgs()` instead of `with()`.
        | `withArgs()` accepts two styles:
        |   - First: Simple validation like `with()`, but pass arguments as array
        |   - Second: A closure that receives all method arguments
        |             and returns true/false based on validity
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);
        $mockedDependencyTwo = Mockery::mock(DependencyTwo::class);

        /*
        | Note: Define method arguments in `withArgs()` exactly like `with()`, just put them in an array. See DependencyTwo->setPerson().
        |
        | Note: The closure will get all arguments passed to method call, in same order.
        |
        | If you change expectation here or method arguments in `Main` class,
        | Mockery will throw NoMatchingExpectationException.
        */
        $mockedDependencyOne->shouldReceive('getOneHundred', 'getNameProperty')->withNoArgs();
        $mockedDependencyOne->shouldReceive('getPassedNumber')->withArgs(fn ($age) => $age === 123); // Check this
        $mockedDependencyOne->shouldReceive('setNameProperty')->withAnyArgs();

        $mockedDependencyTwo->shouldReceive('getTwoHundred')->withNoArgs();

        /*
        | These two lines are same - comment out one and check
        */
        $mockedDependencyTwo->shouldReceive('setPerson')->withArgs(['Ali', 'Yavari', 34]); // Check this
        $mockedDependencyTwo->shouldReceive('setPerson')->withArgs(function ($name, $lastName, $age) { // Check this
            return $name === 'Ali' && $lastName === 'Yavari' && $age === 34;
        });

        $main = new Main($mockedDependencyOne, $mockedDependencyTwo);
        $output = $main->run();

        var_dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_defining_expectation_for_method_argument_partial_mock(): void
    {
        /*
        | With partial mocks, when expectations don't fully match actual method calls:
        | - Mockery won't throw any exceptions.
        | - It executes the real method with real passed arguments normally.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class)->makePartial();

        /*
        | If you change expectation here or method arguments in `Main` class,
        | Mockery will execute the method inside `Main` normally.
        |
        | Note: `with()` and `withArgs()` behave the same here.
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->with(123);
        $mockedDependencyOne->shouldReceive('setNameProperty')->withArgs(['Ali']);

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        var_dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }
}
