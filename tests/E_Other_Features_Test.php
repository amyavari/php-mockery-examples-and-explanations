<?php

declare(strict_types=1);

namespace Tests;

use App\DependencyThree;
use App\MainTwo;
use Mockery;
use PHPUnit\Framework\TestCase;

/**
 * This test class explains these sections:
 *  - Preserving Pass-By-Reference Method Parameter Behavior: https://docs.mockery.io/en/stable/reference/pass_by_reference_behaviours.html
 *  - Mocking Demeter Chains And Fluent Interfaces: https://docs.mockery.io/en/stable/reference/demeter_chains.html
 *  - PHP Magic Methods: https://docs.mockery.io/en/stable/reference/magic_methods.html
 *
 * Note: This file uses `DependencyThree` and `MainTwo` classes to keep things separated and organized.
 */
class E_Other_Features_Test extends TestCase
{
    public function test_pass_by_reference_for_method_parameter(): void
    {
        /*
        | As expected, since the mocked method won't execute normally,
        | if your method accepts parameters by reference, the values won't change.
        | In this case, if you need to modify these parameters,
        | you must handle it manually using closures.
        */
        $mockedDependencyThree = Mockery::mock(DependencyThree::class);

        /*
        | Exactly as expected, `withArgs()` can receive reference arguments
        | and lets you modify their values inside the closure.
        |
        | Note: You must return `true` to pass Mockery's argument validation
        | Note: The `setInternalClassMethodParamMap()` method (explained in official docs)
        |       is incompatible with PHP 8+
        */
        $mockedDependencyThree->shouldReceive('multipleByTen')->withArgs(function (&$number) {
            $number *= 2;

            return true;
        });

        $main = new MainTwo($mockedDependencyThree);
        $output = $main->passedByReference();

        dump($output);

        $this->assertInstanceOf(MainTwo::class, $main);
    }
}
