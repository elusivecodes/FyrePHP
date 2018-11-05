<?php

namespace Frost;

use
    DateTimeZone,
    DateTimeImmutable;

use function
    count,
    is_array,
    is_numeric,
    is_string;

class DateTime
{
    protected $date;

    public function __construct($time = null, ?string $timezone = null)
    {
        if ($timezone) {
            $timezone = new DateTimeZone($timezone);
        }

        $this->date = new DateTimeImmutable('now', $timezone);

        if (is_array($time)) {
            $count = count($time);
            if ($count < 1) {
                return;
            }
            if ($count < 2) {
                $time[] = $this->date->format('m');
            } else {
                $time[1]++;
            }
            if ($count < 3) {
                $time[] = $this->date->format('d');
            }

            $this->date = $this->date->setDate($time[0], $time[1], $time[2]);

            if ($count < 4) {
                return;
            }
            if ($count < 5) {
                $time[] = $this->date->format('i');
            }
            if ($count < 6) {
                $time[] = $this->date->format('s');
            }
            if ($count < 7) {
                $time[] = $this->date->format('v');
            }

            $this->date = $this->date->setTime($time[3], $time[4], $time[5], $time[6] * 1000);
        } else if (is_numeric($time)) {
            $this->date = $this->date->setTimestamp($time);
        } else if (is_string($time)) {
            $this->date = new DateTimeImmutable($time, $timezone);
        } else if ($date instanceof \DateTime || $date instanceof DateTime) {
            $this->date = $this->date->setTimestamp($time->getTimestamp());
        }
    }

    public function pushDate(\DateTimeImmutable &$date): self
    {
        $this->date = &$date;
        return $this;
    }

    use
        Local,
        Utility;

}
