<?php

namespace Frost\Color\Colors;

use function
    max,
    min;

class CMYK extends Base
{
    private $c;
    private $m;
    private $y;
    private $k;

    public function __construct(float $c, float $m, float $y, float $k, float $a = 1)
    {
        parent::__construct($a);

        $this->c = max(0, min(100, $c));
        $this->m = max(0, min(100, $m));
        $this->y = max(0, min(100, $y));
        $this->k = max(0, min(100, $k));
    }

    public function setAlpha(float $a): self
    {
        return new CMYK($this->c, $this->m, $this->y, $this->k, $a);
    }

    public function toCMY(): CMY
    {
        [$c, $m, $y] = static::CMYK2CMY($this->c, $this->m, $this->y, $this->k);
        return new CMY($c, $m, $y, $this->a);
    }

    public function toRGB(): RGB
    {
        return $this->toCMY()->toRGB();
    }

}
