<?php

namespace Frost\Color\Utility;

trait Palette
{

    public function palette(int $shades = 10, int $tints = 10, int $tones = 10): array
    {
        return [
            'shades' => $this->shades($shades),
            'tints' => $this->tints($tints),
            'tones' => $this->tones($tones)
        ];
    }

    public function shades(int $shades = 10): array
    {
        $results = [];
        for ($i = 1; $i <= $shades; $i++) {
            $results[] = $this->_color->shade($i / ($shades + 1));
        }
        return $results;
    }

    public function tints(int $tints = 10): array
    {
        $results = [];
        for ($i = 1; $i <= $tints; $i++) {
            $results[] = $this->_color->tint($i / ($tints + 1));
        }
        return $results;
    }

    public function tones(int $tones = 10): array
    {
        $results = [];
        for ($i = 1; $i <= $tones; $i++) {
            $results[] = $this->_color->tone($i / ($tones + 1));
        }
        return $results;
    }

}
