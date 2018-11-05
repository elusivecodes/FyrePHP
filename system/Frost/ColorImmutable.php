<?php

namespace Frost;

use
    Frost\Color\Colors\Base,
    Frost\Color\ColorInterface;

class ColorImmutable extends Color implements ColorInterface
{

    public function pushColor(Base &$color): self
    {
        return new ColorImmutable($color);
    }

}
