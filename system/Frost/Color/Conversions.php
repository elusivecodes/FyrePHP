<?php

namespace Frost\Color;

use function
    floor,
    fmod,
    max,
    min;

trait Conversions
{

    public static function CMY2CMYK(float $c, float $m, float $y): array
    {
        $k = min($c, $m, $y) / 100;

        return $k === 1 ?
            [0, 0, 0, $k * 100] :
            [
                (($c / 100) - $k) / (1 - $k) * 100,
                (($m / 100) - $k) / (1 - $k) * 100,
                (($y / 100) - $k) / (1 - $k) * 100,
                $k * 100
            ];
    }

    public static function CMY2RGB(float $c, float $m, float $y): array
    {
        return [
            (1 - ($c / 100)) * 255,
            (1 - ($m / 100)) * 255,
            (1 - ($y / 100)) * 255
        ];
    }

    public static function CMYK2CMY(float $c, float $m, float $y, float $k): array
    {
        $k /= 100;

        return [
            (($c / 100) * (1 - $k) + $k) * 100,
            (($m / 100) * (1 - $k) + $k) * 100,
            (($y / 100) * (1 - $k) + $k) * 100
        ];
    }

    public static function HSL2RGB(float $h, float $s, float $l): array
    {
        if ($l == 0) {
            return [0, 0, 0];
        }

        $h /= 360;
        $s /= 100;
        $l /= 100;

        $v2 = $l < 0.5 ?
            $l * (1 + $s) :
            ($l + $s) - ($s * $l);

        $v1 = 2 * $l - $v2;

        return [
            static::RGBHue($v1, $v2, $h + (1 / 3)) * 255,
            static::RGBHue($v1, $v2, $h) * 255,
            static::RGBHue($v1, $v2, $h - (1 / 3)) * 255
        ];
    }

    public static function HSV2RGB(float $h, float $s, float $v): array
    {
        $v /= 100;

        if ($s == 0) {
            return [$v * 255, $v * 255, $v * 255];
        }

        $h = $h / 60 % 6;
        $s /= 100;

        $vi = floor($h);
        $v1 = $v * (1 - $s);
        $v2 = $v * (1 - $s * ($h - $vi));
        $v3 = $v * (1 - $s * (1 - ($h - $vi)));

        $r;
        $g;
        $b;
        if ($vi == 0) {
            $r = $v;
            $g = $v3;
            $b = $v1;
        } else if ($vi == 1) {
            $r = $v2;
            $g = $v;
            $b = $v1;
        } else if ($vi == 2) {
            $r = $v1;
            $g = $v;
            $b = $v3;
        } else if ($vi == 3) {
            $r = $v1;
            $g = $v2;
            $b = $v;
        } else if ($vi == 4) {
            $r = $v3;
            $g = $v1;
            $b = $v;
        } else {
            $r = $v;
            $g = $v1;
            $b = $v2;
        }

        return [$r * 255, $g * 255, $b * 255];
    }

    public static function RGB2CMY($r, $g, $b): array
    {
        $c = 1 - ($r / 255);
        $m = 1 - ($g / 255);
        $y = 1 - ($b / 255);

        return [$c * 100, $m * 100, $y * 100];
    }

    public static function RGB2Luma(float $r, float $g, float $b): float
    {
		$v1 = 0.2126 * ($r / 255);
		$v2 = 0.7152 * ($g / 255);
        $v3 = 0.0722 * ($b / 255);

		return $v1 + $v2 + $v3;
    }

    public static function RGB2HSL(float $r, float $g, float $b): array
    {
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $min = min($r, $g, $b);
        $max = max($r, $g, $b);
        $diff = $max - $min;

        $l = ($max + $min) / 2;

        if ($diff == 0) {
            return [0, 0, $l * 100];
        }

        $s = $l < 0.5 ?
            $diff / ($max + $min) :
            $diff / (2 - $max - $min);

        $deltaR = ((($max - $r) / 6) + ($diff / 2)) / $diff;
        $deltaG = ((($max - $g) / 6) + ($diff / 2)) / $diff;
        $deltaB = ((($max - $b) / 6) + ($diff / 2)) / $diff;

        $h = 0;
        if ($r == $max) {
            $h = $deltaB - $deltaG;
        } else if ($g == $max) {
            $h = (1 / 2) + $deltaR - $deltaB;
        } else if ($b == $max) {
            $h = (2 / 3) + $deltaG - $deltaR;
        }

        $h = fmod($h + 1, 1);

        return [$h * 360, $s * 100, $l * 100];
    }

    public static function RGB2HSV(float $r, float $g, float $b): array
    {
        $r /= 255;
        $g /= 255;
        $b /= 255;

        $min = min($r, $g, $b);
        $max = max($r, $g, $b);
        $diff = $max - $min;

        $v = $max;

        if ($diff == 0) {
            return [0, 0, $v * 100];
        }

        $s = $diff / $max;

        $deltaR = ((($max - $r) / 6) + ($diff / 2)) / $diff;
        $deltaG = ((($max - $g) / 6) + ($diff / 2)) / $diff;
        $deltaB = ((($max - $b) / 6) + ($diff / 2)) / $diff;

        $h = 0;
        if ($r == $max) {
            $h = $deltaB - $deltaG;
        } else if ($g == $max) {
            $h = (1 / 2) + $deltaR - $deltaB;
        } else if ($b == $max) {
            $h = (2 / 3) + $deltaG - $deltaR;
        }

        $h = fmod($h + 1, 1);

        return [$h * 360, $s * 100, $v * 100];
    }

    public static function RGBHue(float $v1, float $v2, float $vH): float
    {
        $vH = fmod($vH + 1, 1);

        if (6 * $vH < 1) {
            return $v1 + ($v2 - $v1) * 6 * $vH;
        }

        if (2 * $vH < 1) {
            return $v2;
        }

        if (3 * $vH < 2) {
            return $v1 + ($v2 - $v1) * ((2 / 3) - $vH) * 6;
        }

        return $v1;
    }

}
