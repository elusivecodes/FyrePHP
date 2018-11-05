<?php

namespace Frost\DateTime;

trait Local
{

    public function getBeat(): int
    {
        return (int) $this->date->format('B');
    }

    public function getDate(): int
    {
        return (int) $this->date->format('j');
    }

    public function getDay(): int
    {
        return (int) $this->date->format('w');
    }

    // getDayName

    public function getDayOfYear(): int
    {
        return (int) $this->date->format('z');
    }

    public function getFullYear(): int
    {
        return (int) $this->date->format('Y');
    }

    public function getHours(): int
    {
        return (int) $this->date->format('G');
    }

    public function getIsoDay(): int
    {
        return (int) $this->date->format('N');
    }

    public function getIsoWeek(): int
    {
        return (int) $this->date->format('W');
    }

    public function getIsoYear(): int
    {
        return (int) $this->date->format('o');
    }

    public function getMilliseconds(): int
    {
        return (int) $this->date->format('v');
    }

    public function getMinutes(): int
    {
        return (int) $this->date->format('i');
    }

    public function getMonth(): int
    {
        return (int) $this->date->format('n') - 1;
    }

    // getMonthName

    public function getQuarter(): int
    {
        return ceil($this->date->format('n') / 3);
    }

    public function getSeconds(): int
    {
        return (int) $this->date->format('s');
    }

    // setBeat

    public function setDate(int $date): self
    {
        $tempDate = $this->date->setDate($this->getFullYear(), $this->getMonth() + 1, $date);
        return $this->pushDate($tempDate);
    }

    // set day

    public function setDayOfYear(int $day): self
    {
        $tempDate = $this->date->setDate($this->getFullYear(), 1, $day);
        return $this->pushDate($tempDate );
    }

    public function setFullYear(int $year, ?int $month = null, ?int $date = null): self
    {
        if ($month === null) {
            $month = $this->getMonth();
        }

        if ($date === null) {
            $date = $this->getDate();
        }

        $tempDate = $this->date->setDate($year, $month + 1, $date);

        return $this->pushDate($tempDate);
    }

    public function setHours(int $hours, ?int $minutes = null, ?int $seconds = null, ?int $millis = null): self
    {
        if ($minutes === null) {
            $minutes = $this->getMinutes();
        }

        if ($seconds === null) {
            $seconds = $this->getMilliseconds();
        }

        if ($millis === null) {
            $millis = $this->getSeconds();
        }

        $tempDate = $this->date->setTime($hours, $minutes, $seconds, $millis * 1000);

        return $this->pushDate($tempDate);
    }

    public function setIsoDay(int $day): self
    {
        $tempDate = $this->date->setISODate($this->getIsoYear(), $this->getIsoWeek(), $day);
        return $this->pushDate($tempDate);
    }

    public function setIsoWeek(int $week, ?int $day = null): self
    {
        if ($day === null) {
            $day = $this->getIsoDay();
        }

        $tempDate = $this->date->setISODate($this->getIsoYear(), $week, $day);

        return $this->pushDate($tempDate);
    }

    public function setIsoYear(int $year, ?int $week = null, ?int $day = null): self
    {
        if ($week === null) {
            $week = $this->getIsoWeek();
        }

        if ($day === null) {
            $day = $this->getIsoDay();
        }

        $tempDate = $this->date->setISODate($year, $week, $day);

        return $this->pushDate($tempDate);
    }

    public function setMinutes(int $minutes, ?int $seconds = null, ?int $millis = null): self
    {
        if ($seconds === null) {
            $seconds = $this->getMilliseconds();
        }

        if ($millis === null) {
            $millis = $this->getSeconds();
        }

        $tempDate = $this->date->setTime($this->getHours(), $minutes, $seconds, $millis * 1000);

        return $this->pushDate($tempDate);
    }

    public function setMonth(int $month, ?int $date = null): self
    {
        if ($date === null) {
            $date = $this->getDate();
        }

        return $this->pushDate(
            $this->date->setDate(
                $this->getFullYear(),
                $month,
                $date
            )
        );
    }

    public function setSeconds(int $seconds, ?int $millis = null): self
    {
        if ($millis = null) {
            $millis = $this->getMilliseconds();
        }

        $tempDate = $this->date->setTime($this->getHours(), $this->getMinutes(), $seconds, $millis * 1000);

        return $this->pushDate($tempDate);
    }

}
