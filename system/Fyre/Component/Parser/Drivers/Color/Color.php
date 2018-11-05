<?php

namespace Fyre\Component\Parser;

class Color extends \Fyre\Driver {

    public function parse($string, $input = FALSE) {
        if ($input === FALSE) {
            return \Fyre\ColorImmutable::fromString(
                $this->_parent->subparse($string)
            );
        }

        if (is_string($input)) {
            return \Fyre\ColorImmutable::fromString($input);
        }
  
        if (is_array($input)) {
            return new \Fyre\ColorImmutable(...$input);
        }
  
        return new \Fyre\ColorImmutable($input);
    }

    public function color($string, $input, $options) {
        $color = $this->parse($string, $input);

        if (isset($options['alpha'])) {
            $color = $color->setAlpha($options['alpha']);
        }

        if (isset($options['brightness'])) {
            $color = $color->setBrightness($options['brightness']);
        }

        if (isset($options['hue'])) {
            $color = $color->setHue($options['hue']);
        }

        if (isset($options['saturation'])) {
            $color = $color->setSaturation($options['saturation']);
        }
    
        return $color;
    }

    public function cmy($string) {
        $cmy = \explode(',', $string);
        return \Fyre\ColorImmutable::fromCMY($cmy[0], $cmy[1], $cmy[2], $cmy[3]);
    }

    public function cmyk($string) {
        $cmyk = \explode(',', $string);
        return \Fyre\ColorImmutable::fromCMYK($cmy[0], $cmy[1], $cmy[2], $cmy[3], $cmy[4]);
    }

    public function hsl($string) {
        $hsl = \explode(',', $string);
        isset($hsl['3']) || $hsl['3'] = 1;
        return \Fyre\ColorImmutable::fromHSL($hsl[0], $hsl[1], $hsl[2], $hsl[3]);
    }

    public function hsv($string) {
        $hsv = \explode(',', $string);
        isset($hsv['3']) || $hsv['3'] = 1;
        return \Fyre\ColorImmutable::fromHSV($hsv[0], $hsv[1], $hsv[2], $hsv[3]);
    }

    public function rgb($string) {
        $rgb = \explode(',', $string);
        return new \Fyre\ColorImmutable($rgb[0], $rgb[1], $rgb[2], $rgb[3]);
    }

    public function alpha($string, $input) {
        return $this->parse($string, $input)->getAlpha();
    }

    public function analogous($string, $input) {
        return $this->parse($string, $input)->analogous();
    }

    public function brightness($string, $input) {
        return $this->parse($string, $input)->getBrightness();
    }

    public function complementary($string, $input) {
        return $this->parse($string, $input)->complementary();
    }

    public function darken($string, $input, $options) {
        isset($options['amount']) || $options['amount'] = 0;
        return $this->parse($string, $input)->darken($options['amount']);
    }

    public function hue($string, $input) {
        return $this->parse($string, $input)->getHue();
    }

    public function lighten($string, $input, $options) {
        isset($options['amount']) || $options['amount'] = 0;
        return $this->parse($string, $input)->lighten($options['amount']);
    }

    public function luma($string, $input) {
        return $this->parse($string, $input)->luma();
    }

    public function mix($string, $input, $options) {
        isset($options['amount']) || $options['amount'] = 0;
        return \Fyre\ColorImmutable::mix($this->parse($string), $this->parse($input), $options['amount']);
    }

    public function multiply($string, $input, $options) {
        return \Fyre\ColorImmutable::multiply($this->parse($string), $this->parse($input));
    }

    public function palette($string, $input, $options) {
        isset($options['shades']) || $options['shades'] = 10;
        isset($options['tints']) || $options['tints'] = 10;
        isset($options['tones']) || $options['tones'] = 10;
        return $this->parse($string)->palette($options['shades'], $options['tints'], $options['tones']);
    }

    public function saturation($string, $input) {
        return $this->parse($string, $input)->getSaturation();
    }

    public function shade($string, $input, $options) {
        isset($options['amount']) || $options['amount'] = 0;
        return $this->parse($string, $input)->shade($options['amount']);
    }

    public function shades($string, $input, $options) {
        isset($options['shades']) || $options['shades'] = 10;
        return $this->parse($string)->shades($options['shades']);
    }

    public function split($string, $input) {
        return $this->parse($string, $input)->split();
    }

    public function tetradic($string, $input) {
        return $this->parse($string, $input)->tetradic();
    }

    public function tint($string, $input, $options) {
        isset($options['amount']) || $options['amount'] = 0;
        return $this->parse($string, $input)->tint($options['amount']);
    }

    public function tints($string, $input, $options) {
        isset($options['tints']) || $options['tints'] = 10;
        return $this->parse($string)->tints($options['tints']);
    }

    public function tone($string, $input, $options) {
        isset($options['amount']) || $options['amount'] = 0;
        return $this->parse($string, $input)->tone($options['amount']);
    }

    public function tones($string, $input, $options) {
        isset($options['tones']) || $options['tones'] = 10;
        return $this->parse($string)->tones($options['tones']);
    }

    public function triadic($string, $input) {
        return $this->parse($string, $input)->triadic();
    }

}
