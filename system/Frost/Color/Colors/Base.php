<?php

namespace Frost\Color\Colors;

use function
    max,
    min;

abstract class Base
{
    protected $a;

    public function __construct(float $a)
    {
        $this->a = max(0, min(1, $a));
    }

    public function darken($amount)
    {
        return $this->toHSL()->darken($amount);
    }

    public function getAlpha(): float
    {
        return $this->a;
    }

    public function getBrightness(): float
    {
        return $this->toHSV()->getBrightness();
    }

    public function getHue(): float
    {
        return $this->toHSV()->getHue();
    }

    public function getSaturation(): float
    {
        return $this->toHSV()->getSaturation();
    }

    public function lighten(float $amount)
    {
        return $this->toHSL()->lighten($amount);
    }

    public function luma(): float
    {
        return $this->toRGB()->luma();
    }

    public function setBrightness(float $v)
    {
        return $this->toHSV()->setBrightness($v);
    }

    public function setHue(float $h)
    {
        return $this->toHSV()->setHue($h);
    }

    public function setSaturation(float $s)
    {
        return $this->toHSV()->setSaturation($s);
    }

    public function shade(float $amount)
    {
        return static::mix($this, new RGB(0, 0, 0), $amount);
    }

    public function tint(float $amount)
    {
        return static::mix($this, new RGB(255, 255, 255), $amount);
    }

    public function toCMY()
    {
        return $this->toRGB()->toCMY();
    }

    public function toCMYK()
    {
        return $this->toCMY()->toCMYK();
    }

    public function toHSL()
    {
        return $this->toRGB()->toHSL();
    }

    public function toHSV()
    {
        return $this->toRGB()->toHSV();
    }

    public function tone(float $amount)
    {
        return static::mix($this, new RGB(127, 127, 127), $amount);
    }

    public function __toString(): string
    {
        return $this->toRGB()->__toString();
    }

}
