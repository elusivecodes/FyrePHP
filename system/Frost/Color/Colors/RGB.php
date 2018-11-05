<?php

namespace Frost\Color\Colors;

use function
    array_search,
    max,
    min,
    round,
    sprintf;

class RGB extends Base
{
    private $r;
    private $g;
    private $b;

    public function __construct(float $r, float $g, float $b, float $a = 1)
    {
        parent::__construct($a);

        $this->r = max(0, min(255, $r));
        $this->g = max(0, min(255, $g));
        $this->b = max(0, min(255, $b));
    }

    public function luma(): float
    {
        return static::RGB2Luma($this->r, $this->g, $this->b);
    }

    public function setAlpha(float $a): self
    {
        return new RGB($this->r, $this->g, $this->b, $this->a);
    }

    public function toHSL(): HSL
    {
        [$h, $s, $l] = static::RGB2HSL($this->r, $this->g, $this->b);
        return new HSL($h, $s, $l, $this->a);
    }

    public function toHSV(): HSV
    {
        [$h, $s, $v] = static::RGB2HSV($this->r, $this->g, $this->b);
        return new HSV($h, $s, $v, $this->a);
    }

    public function toRGB(): self
    {
        return $this;
    }

    public function __toString(): string
    {
        $a = round($this->a * 100) / 100;

        if ($a === 0) {
            return 'transparent';
        }

        $r = round($this->r);
        $g = round($this->g);
        $b = round($this->b);

        if ($a == 1) {
            $rgb = $b | ($g << 8) | ($r << 16);

            $hex = sprintf("#%02x%02x%02x", $r, $g, $b);

            $name = array_search($hex, static::$_colors);

            if ($name) {
                return $name;
            }

            if ($hex[1] === $hex[2] &&
                $hex[3] === $hex[4] &&
                $hex[5] === $hex[6]) {
                return '#'.$hex[1].$hex[3].$hex[5];
            }

            return $hex;
        }

        return 'rgba('.$r.', '.$g.', '.$b.', '.$a.')';
    }

}
