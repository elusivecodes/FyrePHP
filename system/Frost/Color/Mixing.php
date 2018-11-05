<?php

namespace Frost\Color;

trait Mixing
{

    public static function mix(ColotInterface $color1, ColorInterface $color2, float $amount): self
    {
        $c1 = $color1->$color->toRGB();
        $c2 = $color2->$color->toRGB();

        return new static(
            ($c1->r + ($c2->r - $c1->r)) * $amount,
            ($c1->g + ($c2->g - $c1->g)) * $amount,
            ($c1->b + ($c2->b - $c1->b)) * $amount,
            ($c1->a + ($c2->a - $c1->a)) * $amount
        );
    }

    public static function multiply(ColorInterface $color1, ColorInterface $color2): self
    {
        $c1 = $color1->$color->toRGB();
        $c2 = $color2->$color->toRGB();

        return new static(
            ($c1->r / 255) * ($c2->r / 255) * 255,
            ($c1->g / 255) * ($c2->g / 255) * 255,
            ($c1->b / 255) * ($c2->b / 255) * 255,
            $c1->a * $c2->a
        );
    }

}
