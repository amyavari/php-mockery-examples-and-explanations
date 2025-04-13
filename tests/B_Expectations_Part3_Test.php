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
 *  - Declaring Call Count Expectations
 *  - Multiple Calls with Different Expectations
 *  - Expectation Declaration Utilities
 *
 * Official documentation: https://docs.mockery.io/en/stable/reference/expectations.html
 */
class B_Expectations_Part3_Test extends TestCase
{
    protected function tearDown(): void
    {
        parent::tearDown();

        Mockery::close();
    }

    public function test_define_call_count_expectations(): void
    {
        /*
        | Call count expectations act as test assertions.
        | If actual calls don't match what you defined (more or less),
        | Mockery throws InvalidCountException.
        |
        | Note: Without call count expectations, Mockery assumes
        |       anywhere from zero to infinite calls.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        /*
        | Note: We need to tell Mockery when our test (code under test) finishes so it can compare
        |       the actual call counts with expectations. For this, put Mockery::close()
        |       at the end of each test method. This is when Mockery does the comparison.
        |
        |       Since PHPUnit runs tearDown() after each test method in the file,
        |       putting Mockery::close() there ensures all tests get verified.
        |
        | Examples below show matching/non-matching call counts for `getPassedNumber()`
        | (called exactly 4 times). Test them one by one by commenting others out.
        */

        // Passes
        $mockedDependencyOne->shouldReceive('getPassedNumber')->times(4);
        $mockedDependencyOne->shouldReceive('getPassedNumber')->zeroOrMoreTimes();
        $mockedDependencyOne->shouldReceive('getPassedNumber')->atLeast()->times(3);
        $mockedDependencyOne->shouldReceive('getPassedNumber')->atMost()->times(5);
        $mockedDependencyOne->shouldReceive('getPassedNumber')->between(3, 5);

        // Fails
        $mockedDependencyOne->shouldReceive('getPassedNumber')->once();
        $mockedDependencyOne->shouldReceive('getPassedNumber')->twice();
        $mockedDependencyOne->shouldReceive('getPassedNumber')->never();
        // Or try all above expectations with call counts that don't match
        $mockedDependencyOne->shouldReceive('getPassedNumber')->times(3);

        $mockedDependencyOne->shouldReceive('getOneHundred');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->repeatedMethodCalls();

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_define_multiple_calls_with_different_expectations(): void
    {
        /*
        | As you saw in `B_Expectations_Part2_Test`, we can have different return values
        | for multiple calls to mocked class methods. We also have:
        | - `withArgs()` that accepts closure to validate arguments on different calls.
        | - `andReturnUsing()` that accepts closure to calculate return values based on arguments
        |
        | Mockery has another way to handle multiple calls with different expectations:
        | When you add call count expectations, the following `with*()` and `andReturn*()`
        | chains only apply for that number of calls. After those calls, you can define
        | new expectations for subsequent calls to the same method.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        /*
        | For `getPassedNumber()` we defined three expectations:
        |   - First call: must receive `111` and returns `1`
        |   - Second call: must receive `222` and returns `2`
        |   - Third+fourth calls: must receive `333` and returns `3` then `4`
        |
        | Note: Compare with non-call-count version in `test_define_different_return_value_for_each_call()`
        |       in `B_Expectations_Part2_Test` file.
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->once()->with(111)->andReturn(1);
        $mockedDependencyOne->shouldReceive('getPassedNumber')->once()->withArgs(fn (int $num) => $num == 222)->andReturn(2);
        $mockedDependencyOne->shouldReceive('getPassedNumber')->twice()->with(333)->andReturn(3, 4);

        $mockedDependencyOne->shouldReceive('getOneHundred');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->repeatedMethodCalls(num1: 111, num2: 222, num3: 333, num4: 333);

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_define_order_of_method_calls_for_one_mocked_class(): void
    {
        /*
        | If you want to verify the order of method calls in your code,
        | chain the `ordered()` method to those where order matters.
        |
        | Note: Only the call order of methods with `ordered()` will be checked.
        |
        | Note: Mockery compares the order you defined expectations here
        |       with the actual call order in your code.
        |       If it doesn't match, Mockery will throw InvalidOrderException.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);
        $mockedDependencyTwo = Mockery::mock(DependencyTwo::class);

        /*
        | By default, Mockery checks method call order separately for each mocked class.
        | So the order between `DependencyTwo` and `DependencyOne` methods here
        | and in `Main` class doesn't need to match.
        */
        $mockedDependencyTwo->shouldReceive('getTwoHundred')->ordered();
        $mockedDependencyTwo->shouldReceive('setPerson')->ordered();

        /*
        | If you add `ordered()` to `getNameProperty()`, Mockery throws exception
        | since `setNameProperty()` is called before it in `Main` class.
        |
        | You can change the order of `getPassedNumber()`, `getOneHundred()` and `setNameProperty()` here
        | or in the `Main` class and see Mockery throw InvalidOrderException when the orders don't match.
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->andReturn(1)->ordered();
        $mockedDependencyOne->shouldReceive('getOneHundred')->once()->ordered();
        $mockedDependencyOne->shouldReceive('getNameProperty');
        $mockedDependencyOne->shouldReceive('setNameProperty')->with('Ali')->ordered();

        $main = new Main($mockedDependencyOne, $mockedDependencyTwo);
        $output = $main->run();

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_define_order_of_method_calls_with_grouping(): void
    {
        /*
        | When checking method call order, you can group methods together
        | where their relative order doesn't matter.
        |
        | Note: You can have as many grouped methods as needed - groups and single methods
        |       will be compared against each other.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        /*
        | As shown, even though `setNameProperty()` is called before `getNameProperty()`
        | in `Main` class, Mockery won't throw exceptions when they're grouped.
        |
        | Current definitions specify this order:
        | - 1. `getPassedNumber()`
        | - 2. `getOneHundred()`
        | - 3. `group1` methods (both) - order between them doesn't matter
        |
        | If you move ONLY `getNameProperty()` above `getOneHundred()`:
        | - 1. `getPassedNumber()`
        | - 2. `group1` methods (both)
        | - 3. `getOneHundred()`
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->ordered();
        $mockedDependencyOne->shouldReceive('getOneHundred')->ordered();
        $mockedDependencyOne->shouldReceive('getNameProperty')->ordered('group1');
        $mockedDependencyOne->shouldReceive('setNameProperty')->ordered('group1');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->run();

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_define_order_of_method_calls_between_multiple_mocked_classes(): void
    {
        /*
        | If you want to check method call order even across multiple mocked classes,
        | chain `globally()` before `ordered()` for the methods you want to check their orders globally.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);
        $mockedDependencyTwo = Mockery::mock(DependencyTwo::class);

        /*
        | Note: Only `DependencyTwo->getTwoHundred()` and `DependencyOne->setNameProperty()`
        | are compared globally since they have `globally()`. Removing it from either one
        | stops Mockery from comparing their order.
        |
        | Or just move `DependencyTwo->getTwoHundred()` above `DependencyOne->setNameProperty()` in `Main` class.
        */
        $mockedDependencyTwo->shouldReceive('getTwoHundred')->globally()->ordered(); // Check this
        $mockedDependencyTwo->shouldReceive('setPerson')->ordered();

        $mockedDependencyOne->shouldReceive('getPassedNumber')->ordered();
        $mockedDependencyOne->shouldReceive('getOneHundred')->ordered();
        $mockedDependencyOne->shouldReceive('getNameProperty');
        $mockedDependencyOne->shouldReceive('setNameProperty')->globally()->ordered(); // Check this

        $main = new Main($mockedDependencyOne, $mockedDependencyTwo);
        $output = $main->run();

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }

    public function test_define_default_expectations_and_override_them(): void
    {
        /*
        | As explained earlier, re-defining method expectations has two cases:
        | - Without call count: Mockery ignores new definitions
        | - With call count: New definitions apply to subsequent calls
        |
        | To completely override previous expectations (ignore them),
        | chain `byDefault()` on the first definition.
        | This makes Mockery use only the latest definition.
        */
        $mockedDependencyOne = Mockery::mock(DependencyOne::class);

        /*
        | Since first `getPassedNumber()` expectations have `byDefault()`,
        | Mockery ignores them and uses the second definition.
        |
        | Behavior changes:
        | - Comment out first definition: no behavior change
        | - Remove `byDefault()` from first: second definition gets ignored
        | - Comment out second definition: first definition (with `byDefault()`) takes effect
        */
        $mockedDependencyOne->shouldReceive('getPassedNumber')->andReturn(111)->byDefault();
        $mockedDependencyOne->shouldReceive('getPassedNumber')->andReturn(222);

        $mockedDependencyOne->shouldReceive('getOneHundred');

        $main = new Main($mockedDependencyOne, new DependencyTwo);
        $output = $main->repeatedMethodCalls();

        dump($output);

        $this->assertInstanceOf(Main::class, $main);
    }
}
