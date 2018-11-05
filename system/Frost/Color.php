<?php

namespace Frost;

use
    Frost\Color\Colors\Base,
    Frost\Color\Colors\RGB,
    Frost\Color\Colors\HSL,
    Frost\Color\Utility\Attributes,
    Frost\Color\Utility\Manipulation,
    Frost\Color\Utility\Palette,
    Frost\Color\Utility\Schemes,
    Frost\Color\ColorInterface,
    Frost\Color\Conversions,
    Frost\Color\Create,
    Frost\Color\Mixing,
    Frost\Color\Vars;

class Color implements ColorInterface
{
    public $color;

    use
        Conversions,
        Create,
        Mixing,
        Vars,
        Attributes,
        Manipulation,
        Palette,
        Schemes;

    public function __construct($a = 0, int $b = 1, ?int $c = null, int $d = 1)
    {
        if ($c !== null) {
            $this->color = new RGB($a, $b, $c, $d);
        } else if ($a instanceof Base) {
            $this->color = &$a;
        } else if ($a instanceof Color) {
            $this->color = &$a->$color;
        } else {
            $this->color = new HSL(0, 0, $a, $b);
        }
    }

    public function pushColor(Base &$color): self
    {
        $this->color = &$color;
        return $this;
    }

    public function __toString(): string
    {
        return $this->color->__toString();
    }

}
