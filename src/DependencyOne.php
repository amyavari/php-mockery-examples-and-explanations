<?php

declare(strict_types=1);

namespace App;

class DependencyOne
{
    public string $name = 'default_name';

    public function getPassedNumber(int $number)
    {
        return $number;
    }

    public function getOneHundred(): int
    {
        return 100;
    }

    public function setNameProperty(string $name): string
    {
        return $this->name = $name;
    }

    public function getNameProperty(): string
    {
        return $this->name;
    }
}
