<?php

declare(strict_types=1);

namespace App;

class DependencyTwo
{
    public function getTwoHundred(): int
    {
        return 200;
    }

    public function setPerson(string $name, string $lastName, int $age): string
    {
        return sprintf('%s %s age: %s', $name, $lastName, $age);
    }
}
