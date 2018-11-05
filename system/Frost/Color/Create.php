<?php

namespace Frost\Color;

use
    Frost\Color\Colors\CMY,
    Frost\Color\Colors\CMYK,
    Frost\Color\Colors\HSL,
    Frost\Color\Colors\HSV,
    Frost\Color\Colors\RGB;

use function
    hexdec,
    preg_match,
    strtolower;

trait Create
{

    public static function fromCMY(float $c, float $m, float $y, float $a = 1): self
    {
        $cmy = new CMY($c, $m, $y, $a);
        return new static($cmy);
    }

    public static function fromCMYK(float $c, float $m, float $y, float $k, float $a = 1): self
    {
        $cmyk = new CMYK($c, $m, $y, $k, $a);
        return new static($cmyk);
    }

    public static function fromHSL(float $h, float $s, float $l, float $a = 1): self
    {
        $hsl = new HSL($h, $s, $l, $a);
        return new static($hsl);
    }

    public static function fromHSV(float $h, float $s, float $v, float $a = 1): self
    {
        $hsv = new HSV($h, $s, $v, $a);
        return new static($hsv);
    }

    public static function fromRGB(float $r, float $g, float $b, float $a = 1): self
    {
        $rgb = new RGB($r, $g, $b, $a);
        return new static($rgb);
    }

    public static function fromString(string $string): self
    {
        $string = strtolower($string);

        if ($string === 'transparent') {
            return new static(0, 0, 0, 0);
        }

        if (isset(static::$_colors[$string])) {
            $string = static::$_colors[$string];
        }

        if (preg_match(static::$_hex_regex, $string, $match)) {
            return new static(
                hexdec($match[1]),
                hexdec($match[2]),
                hexdec($match[3])
            );
        }

        if (preg_match(static::$_hex_regex_short, $string, $match)) {
            return new static(
                hexdec($match[1].$match[1]),
                hexdec($match[2].$match[2]),
                hexdec($match[3].$match[3])
            );
        }

        if (preg_match(static::$_rgb_regex, $string, $match)) {
            return new static($match[1], $match[2], $match[3]);
        }

        if (preg_match(static::$_rgba_regex, $string, $match)) {
            return new static($match[1], $match[2], $match[3], $match[4]);
        }

        if (preg_match(static::$_hsl_regex, $string, $match)) {
            return static::fromHSL($match[1], $match[2], $match[3]);
        }

        if (preg_match(static::$_hsla_regex, $string, $match)) {
            return static::fromHSL($match[1], $match[2], $match[3], $match[4]);
        }

        return new static(0, 0, 0);
    }

}
