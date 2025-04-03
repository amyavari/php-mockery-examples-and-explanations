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
            'dependency_two->setPerson()' => $this->dependencyTwo->setPerson('Ali', 'Yavari', 34),
        ];
    }

    public function repeatedMethodCalls($num1 = 123, $num2 = 123, $num3 = 123, $num4 = 123): array
    {
        return [
            'dependency_one->getPassedNumber('.$num1.')-first' => $this->dependencyOne->getPassedNumber($num1),
            'dependency_one->getPassedNumber('.$num2.')-second' => $this->dependencyOne->getPassedNumber($num2),
            'dependency_one->getPassedNumber('.$num3.')-third' => $this->dependencyOne->getPassedNumber($num3),
            'dependency_one->getPassedNumber('.$num4.')-forth' => $this->dependencyOne->getPassedNumber($num4),
            'dependency_one->getOneHundred()-first' => $this->dependencyOne->getOneHundred(),
            'dependency_one->getOneHundred()-second' => $this->dependencyOne->getOneHundred(),
            'dependency_one->getOneHundred()-third' => $this->dependencyOne->getOneHundred(),
        ];
    }
}
