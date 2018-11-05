<?php

namespace Frost\Color\Colors;

use function
    fmod,
    max,
    min;

class HSV extends Base
{
    protected $h;
    protected $s;
    protected $v;

    public function __construct(float $h, float $s, float $v, float $a = 1)
    {
        parent::__construct($a);

        $this->h = fmod($h, 360);
        $this->s = max(0, min(100, $s));
        $this->v = max(0, min(100, $v));
    }

    public function getBrightness(): float
    {
        return $this->v;
    }

    public function getHue(): float
    {
        return $this->h;
    }

    public function getSaturation(): float
    {
        return $this->s;
    }

    public function setAlpha(float $a): self
    {
        return new HSV($this->h, $this->s, $this->v, $a);
    }

    public function setBrightness(float $v): self
    {
        return new HSV($this->h, $this->s, $v, $this->a);
    }

    public function setHue(float $h): self
    {
        return new HSV($h, $this->s, $this->v, $this->a);
    }

    public function setSaturation(float $s): self
    {
        return new HSV($this->h, $s, $this->v, $this->a);
    }

    public function toHSV(): self
    {
        return $this;
    }

    public function toRGB(): RGB
    {
        [$r, $g, $b] = static::HSV2RGB($this->h, $this->s, $this->v);
        return new RGB($r, $g, $b, $this->a);
    }

}
