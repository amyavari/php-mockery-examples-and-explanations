<?php

declare(strict_types=1);

namespace App;

class DependencyThree
{
    public function multipleByTen(int &$number): void
    {
        $number *= 10;
    }
}
