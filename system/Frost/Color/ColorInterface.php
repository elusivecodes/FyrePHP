<?php

namespace Frost\Color;

use
    Frost\Color\Colors\Base;

interface ColorInterface
{

    public static function fromCMY(float $c, float $m, float $y, float $a = 1);
    public static function fromCMYK(float $c, float $m, float $y, float $k, float $a = 1);
    public static function fromHSL(float $h, float $s, float $l, float $a = 1);
    public static function fromHSV(float $h, float $s, float $v, float $a = 1);
    public static function fromRGB(float $r, float $g, float $b, float $a = 1);
    public static function fromString(string $string);

    public static function CMY2CMYK(float $c, float $m, float $y): array;
    public static function CMY2RGB(float $c, float $m, float $y): array;
    public static function CMYK2CMY(float $c, float $m, float $y, float $k): array;
    public static function HSL2RGB(float $h, float $s, float $l): array;
    public static function HSV2RGB(float $h, float $s, float $v): array;
    public static function RGB2CMY(float $r, float $g, float $b): array;
    public static function RGB2Luma(float $r, float $g, float $b): float;
    public static function RGB2HSL(float $r, float $g, float $b): array;
    public static function RGB2HSV(float $r, float $g, float $b): array;

    public static function mix(ColorInterface $color1, ColorInterface $color2, float $amount);
    public static function multiply(ColorInterface $color1, ColorInterface $color2);

    public function getAlpha(): float;
    public function getBrightness(): float;
    public function getHue(): float;
    public function getSaturation(): float;
    public function luma(): float;
    public function setAlpha(float $alpha);
    public function setBrightness(float $brightness);
    public function setHue(float $hue);
    public function setSaturation(float $saturation);

    public function darken(float $amount);
    public function lighten(float $amount);
    public function shade(float $amount);
    public function tint(float $amount);
    public function tone(float $amount);

    public function palette(int $shades = 10, int $tints = 10, int $tones = 10): array;
    public function shades(int $shades = 10): array;
    public function tints(int $tints = 10): array;
    public function tones(int $tones = 10): array;

    public function analogous(): array;
    public function complementary(): array;
    public function split(): array;
    public function tetradic(): array;
    public function triadic(): array;

    public function pushColor(Base $color);
    public function __toString(): string;

}
