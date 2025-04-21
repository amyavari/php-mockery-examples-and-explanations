<?php

declare(strict_types=1);

namespace Tests;

use App\DependencyOne;
use App\DependencyTwo;
use App\Main;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * This test class explains the `Alternative shouldReceive Syntax` section.
 *  - Alternative shouldReceive Syntax for mocks
 *  - Alternative shouldReceive Syntax for spies
 *
 * Official documentation: https://docs.mockery.io/en/stable/reference/alternative_should_receive_syntax.html
 *                         https://docs.mockery.io/en/stable/reference/spies.html#alternative-shouldreceive-syntax
 */
class D_Alternative_Should_Test extends TestCase
{
    public function test_alternative_should_receive_for_mocks(): void
    {
        /*
        | Mockery lets you use a simpler way to define method calls.
        | Instead of writing `$mocked->shouldReceive($methodName)->with(...$args)`,
        | you can define methods just like you would on a real object of that class.
        |
        | This works when you chain the `allows()` or `expects()` method on the mock, like:
        | `$mocked->allows()->methodName(...$args)`
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        /*
        | The key difference between `allows()` and `expects()` is:
        | - `allows()` behaves like `zeroOrMoreTimes()` in call count expectations (can't be changed).
        | - `expects()` behaves, by default, like `once()` in call count expectations, or
        |   you can declare call count expectations as normal
        */
        $mockedDependencyOne->allows()->getPassedNumber(123)->andReturn(1); // Zero or more calls (allows behavior)
        $mockedDependencyOne->expects()->getOneHundred()->once()->andReturn(2); // Exactly one call required (explicit expectation)
        $mockedDependencyOne->expects()->setNameProperty('Ali')->andReturn('Ali'); // Implicit `once()` expectation (default for expects)
        $mockedDependencyOne->expects()->getNameProperty()->andReturn('Yavari');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_alternative_should_receive_for_spies(): void
    {
        /*
        | For spies, this only applies to the `shouldHaveReceived()` method.
        */
        $spyDependencyOne = Mockery::spy(DependencyOne::class);

        /*
        | The `shouldHaveReceived()` syntax changes from:
        | `$spy->shouldHaveReceived($methodName)->with(...$args)`
        | to:
        | `$spy->shouldHaveReceived()->methodName(...$args)`
        */
        $main = new Main($spyDependencyOne, new DependencyTwo);
        $output = $main->run();

        dump($output);

        $spyDependencyOne->shouldHaveReceived()->getPassedNumber(123)->once();
        $spyDependencyOne->shouldHaveReceived()->getOneHundred()->once();
        $spyDependencyOne->shouldHaveReceived()->setNameProperty('Ali');
        $spyDependencyOne->shouldHaveReceived()->getNameProperty();

        $this->assertInstanceOf(Main::class, $main);
    }
}
