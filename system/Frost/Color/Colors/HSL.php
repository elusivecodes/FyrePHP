<?php

namespace Frost\Color\Colors;

use function
    fmod,
    max,
    min;

class HSL extends Base
{
    protected $h;
    protected $s;
    protected $l;

    public function __construct(float $h, float $s, float $l, float $a = 1)
    {
        parent::__construct($a);

        $this->h = fmod($h, 360);
        $this->s = max(0, min(100, $s));
        $this->l = max(0, min(100, $l));
    }

    public function darken(float $amount): self
    {
        $l = $this->l - ($this->l * $amount);
        return new HSL($this->h, $this->s, $l, $this->a);
    }

    public function lighten(float $amount): self
    {
        $l = $this->l + ((100 - $this->l) * $amount);
        return new HSL($this->h, $this->s, $l, $this->a);
    }

    public function setAlpha(float $a): self
    {
        return new HSL($this->h, $this->s, $this->l, $a);
    }

    public function toHSL(): self
    {
        return $this;
    }

    public function toRGB(): RGB
    {
        [$r, $g, $b] = static::HSL2RGB($this->h, $this->s, $this->l);
        return new RGB($r, $g, $b, $this->a);
    }

}
