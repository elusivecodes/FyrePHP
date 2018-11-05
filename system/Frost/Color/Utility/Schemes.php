<?php

namespace Frost\Color\Utility;

trait Schemes
{

    public function analogous(): array
    {
        return [
            new static($this->_color->setHue($this->_color->getHue() + 30)),
            new static($this->_color->setHue($this->_color->getHue() + 330))
        ];
    }

    public function complementary(): array
    {
        return new static($this->_color->setHue($this->_color->getHue() + 180));
    }

    public function split(): array
    {
        return [
            new static($this->_color->setHue($this->_color->getHue() + 150)),
            new static($this->_color->setHue($this->_color->getHue() + 210))
        ];
    }

    public function tetradic(): array
    {
        return [
            new static($this->_color->setHue($this->_color->getHue() + 60)),
            new static($this->_color->setHue($this->_color->getHue() + 180)),
            new static($this->_color->setHue($this->_color->getHue() + 240))
        ];
    }

    public function triadic(): array
    {
        return [
            new static($this->_color->setHue($this->_color->getHue() + 120)),
            new static($this->_color->setHue($this->_color->getHue() + 240))
        ];
    }

}
