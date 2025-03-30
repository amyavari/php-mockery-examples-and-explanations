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
 *  - Declaring Return Value Expectations
 *  - Setting Public Properties
 *
 * Official documentation: https://docs.mockery.io/en/stable/reference/expectations.html
 */
class B_Expectations_PartTwoTest extends TestCase
{
    public function test_define_simple_return_value(): void
    {
        /*
        | Defining method return values is simple.
        | After setting all expectations, chain the `andReturn()` method.
        |
        | Note: Only `shouldReceive()` is required - others are optional
        |
        | Note: Return value type must match the method's defined return type.
        |       A type mismatch will make Mockery throw TypeError.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        /*
        | These methods will return the defined values on every call.
        |
        | `getPassedNumber()` has no return type, so it can return anything.
        | `getOneHundred()` has `int` return type - passing non-integer values
        | or values that can't be cast to integer to `andReturn()` will make Mockery throw TypeError.
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->andReturn('Ali');
        $mockedDependencyOne->shouldReceive('getOneHundred')->andReturn(2);

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->repeatedMethodCalls();

        var_dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_define_different_return_value_for_each_call(): void
    {
        /*
        | If your method is called multiple times in test and should return different values each time,
        | pass expected values in order to `andReturn()`.
        |
        | Note: If you don't declare call count expectations (as you'll learn later),
        |       only one `shouldReceive()` per method is allowed and extra definitions will be ignored by Mockery.
        |       So all expectations for the method must be defined in this single `shouldReceive()`.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        /*
        | Each method call gets the next defined return value in sequence.
        |
        | Note: If you define fewer return values than calls, Mockery repeats the last value.
        | Note: If you define more return values than calls, Mockery uses only what's needed.
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->andReturn('Ali', true, 1000);
        $mockedDependencyOne->shouldReceive('getOneHundred')->andReturn(2, 4, 6, 8, 10);

        /*
        | Since `getPassedNumber()` was already defined without call count expectation, Mockery ignores this one.
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->andReturn('new definition');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->repeatedMethodCalls();

        var_dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_define_return_value_by_closure(): void
    {
        /*
        | For dynamic return value, maybe based on arguments passed to method call,
        | us `andReturnUsing()` instead of `andReturn()`. It accept a closure to calculate return value.
        |
        | Note: The closure will get all arguments passed to method call, in same order.
        */
        $mockedDependencyTwo = Mockery::mock(DependencyTwo::class);

        /*
        | Note: `DependencyTwo` class is mocked here.
        */
        $mockedDependencyTwo->shouldReceive('getTwoHundred')->andReturnUsing(fn () => random_int(1, 1000));
        $mockedDependencyTwo->shouldReceive('setPerson')->andReturnUsing(function ($name, $lastName, $age) {
            return sprintf('Name: %s, last name: %s, age: %s', $name, $lastName, $age);
        });

        $main = new Main(new DependencyOne, $mockedDependencyTwo);
        $output = $main->run();

        var_dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_set_public_property(): void
    {
        /*
        | Sometimes your mocked class method needs to update its class properties.
        | For public properties, use `set()` or `andSet()` to make Mockery handle this.
        |
        | Note: If you have other methods that read this property and should work normally,
        | you must chain `passthru()` for those methods.
        | Alternatively, use partial mock and don't define expectations for them.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        $mockedDependencyOne->shouldReceive('getOneHundred');
        $mockedDependencyOne->shouldReceive('getPassedNumber');

        /*
        | As you can see, `setNameProperty()` won't actually execute - it just returns whatever value you defined (if you defined one).
        |
        | You can think of this setup as doing two things:
        | 1. Defining the mock return value: $mockedDependencyOne->shouldReceive('setNameProperty')->andReturn('Wow');
        | 2. Directly setting the property: $mockedDependencyOne->name = 'Ali Mohammad Yavari'
        |
        | `getNameProperty()` returns the $name property value because of `passthru()`.
        | Remove `passthru()` and it will return null or any value you define (as explained earlier).
        */
        $mockedDependencyOne->shouldReceive('setNameProperty')->andSet('name', 'Ali Mohammad Yavari')->andReturn('Wow');
        $mockedDependencyOne->shouldReceive('getNameProperty')->passthru();

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        var_dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }
}
