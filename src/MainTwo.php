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
}
