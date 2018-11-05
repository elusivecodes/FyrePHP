<?php

namespace Frost\DateTime;

trait Utility
{

    public function add($interval): self
    {

    }

    public function dateSuffix(): string
    {
        return $this->date->format('S');
    }

    public function daysInMonth(): int
    {
        return (int) $this->date->format('t');
    }

    public function daysInYear(): int
    {
        return $this->isLeapYear() ?
            366 :
            365;
    }

    public function diff($other, $absolute): \DateInterval
    {
        return $this->date->diff($other, $absolute);
    }

    public function format($format): string
    {
        return $this->date->format($format);
    }

    public function isDST(): bool
    {
        return (bool) $this->date->format('I');
    }

    public function isLeapYear(): bool
    {
        return (bool) $this->date->format('L');
    }

    // isoWeeksInYear

}
