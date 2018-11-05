<?php

namespace Frost\Color\Utility;

trait Attributes
{

    public function getAlpha(): float
    {
        return $this->_color->getAlpha();
    }

    public function getBrightness(): float
    {
        return $this->_color->getBrightness();
    }

    public function getHue(): float
    {
        return $this->_color->getHue();
    }

    public function getSaturation(): float
    {
        return $this->_color->getSaturation();
    }

    public function luma(): float
    {
        return $this->_color->luma();
    }

    public function setAlpha(float $alpha): self
    {
        return $this->pushColor($this->_color->setAlpha($alpha));
    }

    public function setBrightness(float $brightness): self
    {
        return $this->pushColor($this->_color->setBrightness($brightness));
    }

    public function setHue(float $hue): self
    {
        return $this->pushColor($this->_color->setHue($hue));
    }

    public function setSaturation(float $saturation): self
    {
        return $this->pushColor($this->_color->setSaturation($saturation));
    }

}
