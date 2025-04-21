<?php

declare(strict_types=1);

namespace Tests;

use App\DependencyOne;
use App\DependencyTwo;
use App\Main;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * This test class explains the `Argument Validation` section.
 *
 * Official documentation: https://docs.mockery.io/en/stable/reference/argument_validation.html
 */
class C_Argument_Validation_Test extends TestCase
{
    public function test_use_hamcrest_matchers(): void
    {
        /*
        | As you learned in the `B_Expectations_Part1_Test` section, there are several ways
        | to validate arguments passed to mocked methods:
        | - Using the `with()` method and passing expected values
        | - Using the `withArgs()` method and passing a closure for manual validation
        |
        | There is a third option in Mockery: using `Hamcrest` matchers.
        |
        | What is Hamcrest?
        | `Hamcrest` is a matcher library designed to combine matching rules into flexible expressions.
        | Originally created for Java, it's now available in most programming languages.
        | You can see the official PHP version here: https://github.com/hamcrest/hamcrest-php
        |
        | When you need to handle complex assertions without using closures, you can use
        | the `with()` method with `Hamcrest` functions, available either as global functions
        | or as static methods on the `\Hamcrest\Matchers` class.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        /*
        | These are equivalent (they do the same thing).
        |
        | Note: Global functions can sometimes cause namespace issues
        | or conflicts with other packages.
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->with(123);
        $mockedDependencyOne->shouldReceive('getPassedNumber')->with(\Hamcrest\Matchers::identicalTo(123));
        $mockedDependencyOne->shouldReceive('getPassedNumber')->with(identicalTo(123));

        $mockedDependencyOne->shouldReceive('getOneHundred')->withNoArgs();
        $mockedDependencyOne->shouldReceive('setNameProperty');
        $mockedDependencyOne->shouldReceive('getNameProperty');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_compare_mockery_generic_matchers_and_hamcrest_matchers(): void
    {
        /*
        | As Mockery states, for most common scenarios, there are equivalent Mockery implementations as well.
        |
        | Below is a comparison of basic, equivalent options you can choose from:
        |-------------------------------------------------------------------------------------------------------
        | Simple Mockery method         |   Mockery generic matchers            |    Hamcrest matchers
        |-------------------------------------------------------------------------------------------------------
        | with($value)                  | -                                     | equalTo($value) (loose comparison, `==`)
        | with($value)                  | -                                     | identicalTo($value) (strict comparison, `===`)
        | withAnyArgs()                 | \Mockery::any()                       | anything()
        | -                             | \Mockery::type($type)                 | typeOf($type) *Built-in type checkers for each type
        | withArgs($closure)            | \Mockery::on($closure)                | -
        | -                             | \Mockery::pattern($pattern)           | matchesPattern($pattern)
        | -                             | \Mockery::ducktype(...$methods)       | -
        | -                             | \Mockery::capture($variable)          | -
        | -                             | \Mockery::not($value)                 | not($value)
        | withSomeOfArgs($args)         | \Mockery::anyOf($args)                | anyOf($args)
        | -                             | \Mockery::notAnyOf($notAnyOf)         | -
        | -                             | \Mockery::subset($array)              | hasEntry() / hasKeyValuePair()
        | -                             | \Mockery::contains($values)           | contains($values)
        | -                             | \Mockery::hasKey($key)                | hasKey($key)
        | -                             | \Mockery::hasValue($value)            | hasValue($value)
        | withNoArgs()                  | -                                     | -
        |-------------------------------------------------------------------------------------------------------
        | Note: Hamcrest offers additional matchers. Check them here: https://github.com/hamcrest/hamcrest-php
        |
        | For full definitions of the above matchers, refer to Mockery's official documentation:
        | https://docs.mockery.io/en/stable/reference/argument_validation.html
        */
    }
}
