<?php

namespace Frost;

class Number
{
    private $number;

    public function __construct(float $number = 0)
    {
        $this->number = $number;
    }

    public function __toString(): string
    {
        return (string) $this->number;
    }

    public function add(float $number): self
    {
        return $this->pushNumber($this->number + $number);
    }

    public function dividedBy(float $number): self
    {
        return $this->pushNumber($this->number / $number);
    }

    public function multiply(float $number): self
    {
        return $this->pushNumber($this->number * $number);
    }

    public function pushNumber(float $number): self
    {
        $this->number = $number;
        return $this;
    }

    public function subtract(float $number): self
    {
        return $this->pushNumber($this->number - $number);
    }

}
