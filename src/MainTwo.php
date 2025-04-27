<?php

declare(strict_types=1);

namespace App;

class MainTwo
{
    public function __construct(protected DependencyThree $dependencyThree) {}

    public function passedByReference(): array
    {
        $number = 4;

        $this->dependencyThree->multipleByTen($number); // This changes number to 40

        return [
            'dependency_three->multipleByTen(4)' => $number,
        ];
    }

    public function chainedMethods(): array
    {
        // Output is:  ['number' => 10, 'string' => 'Ali', 'bool' => true, 'final' => 'Wow']
        $output = $this->dependencyThree->addNumber(10)->addString('Ali')->addBool(true)->finalAdd('Wow');

        return [
            'dependency_three->addNumber(10)->addString("Ali")->addBool(true)->finalAdd("Wow")' => $output,
        ];
    }
}
