<?php

declare(strict_types=1);

namespace App;

/**
 * Only used by `MainTwo` class.
 */
class DependencyThree
{
    protected array $data;

    public function multipleByTen(int &$number): void
    {
        $number *= 10;
    }

    public function addNumber(int $number): self
    {
        $this->data['number'] = $number;

        return $this;
    }

    public function addString(string $string): self
    {
        $this->data['string'] = $string;

        return $this;
    }

    public function addBool(bool $bool): self
    {
        $this->data['bool'] = $bool;

        return $this;
    }

    public function finalAdd(mixed $final): array
    {
        $this->data['final'] = $final;

        return $this->data;
    }

    public function __call($name, $arguments)
    {
        return $name;
    }
}
