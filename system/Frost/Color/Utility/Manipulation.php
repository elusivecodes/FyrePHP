<?php

namespace Frost\Color\Utility;

trait Manipulation
{

    public function darken(float $amount): self
    {
        return $this->pushColor($this->_color->darken($amount));
    }

    public function lighten(float $amount): self
    {
        return $this->pushColor($this->_color->lighten($amount));
    }

    public function shade(float $amount): self
    {
        return $this->pushColor($this->_color->shade($amount));
    }

    public function tint(float $amount): self
    {
        return $this->pushColor($this->_color->tint($amount));
    }

    public function tone(float $amount): self
    {
        return $this->pushColor($this->_color->tone($amount));
    }

}
