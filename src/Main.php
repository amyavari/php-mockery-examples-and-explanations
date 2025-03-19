<?php

declare(strict_types=1);

namespace App;

class Main
{
    public function __construct(protected DependencyOne $dependencyOne, protected DependencyTwo $dependencyTwo) {}

    public function run(): array
    {
        return [
            'dependency_one->getPassedNumber(123)' => $this->dependencyOne->getPassedNumber(123),
            'dependency_one->getOneHundred()' => $this->dependencyOne->getOneHundred(),
            'dependency_one->setNameProperty("Ali")' => $this->dependencyOne->setNameProperty('Ali'),
            'dependency_one->getNameProperty()' => $this->dependencyOne->getNameProperty(),
            'dependency_two->getTwoHundred()' => $this->dependencyTwo->getTwoHundred(),
        ];
    }
}
