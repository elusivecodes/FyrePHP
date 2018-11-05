<?php

namespace Frost\Color\Colors;

use function
    max,
    min;

class CMY extends Base
{
    private $c;
    private $m;
    private $y;

    public function __construct(float $c, float $m, float $y, float $a = 1)
    {
        parent::__construct($a);

        $this->c = max(0, min(100, $c));
        $this->m = max(0, min(100, $m));
        $this->y = max(0, min(100, $y));
    }

    public function setAlpha(float $a): self
    {
        return new CMY($this->c, $this->m, $this->y, $a);
    }

    public function toCMYK(): CMYK
    {
        [$c, $m, $y, $k] = static::CMY2CMYK($this->c, $this->m, $this->y);
        return new CMYK($c, $m, $y, $k, $this->a);
    }

    public function toRGB(): RGB
    {
        [$r, $g, $b] = static::CMY2RGB($this->c, $this->m, $this->y);
        return new RGB($r, $g, $b, $this->a);
    }

}
