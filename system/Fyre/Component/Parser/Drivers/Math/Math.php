<?php

namespace Fyre\Component\Parser;

class Math extends \Fyre\Driver {

    private static function compute(string $string) {
        $priority_pattern = '/(-?\d+(?:\.\d+)?)[\+-\*\/%]*([\*\/%])(-?\d+(?:\.\d+)?)/';
        while (\preg_match($priority_pattern, $string, $match)) {
            if ($match[2] === '*') {
                $replace = $match[1] * $match[3];
            } else if ($match[2] === '/') {
                $replace = $match[1] / $match[3];
            } else if ($match[2] === '%') {
                $replace = $match[1] % $match[3];
            }

            $string = \preg_replace($priority_pattern, $replace, $string, 1);
        }

        $secondary_pattern = '/(-?\d+(?:\.\d+)?)[\+-\*\/%]*([\+-])(\d+(?:\.\d+)?)/';
        while (\preg_match($secondary_pattern, $string, $match)) {
            if ($match[2] === '+') {
                $replace = $match[1] + $match[3];
            } else if ($match[2] === '-') {
                $replace = $match[1] - $match[3];
            }

            $string = \preg_replace($secondary_pattern, $replace, $string, 1);
        }

        return (float) $string;
    }

    public static function format($value, $options) {

        $round = FALSE;
        if (isset($options['round'])) {
            $round = $options['round'];
        }

        $decimals = 0;
        if (isset($options['decimals'])) {
            $decimals = (int) $options['decimals'];
        }

        $decimal = '.';
        if (isset($options['decimal'])) {
            $decimal = $options['decimal'];
        }

        $thousands = '';
        if (isset($options['decimal'])) {
            $thousands = $options['thousands'];
        }

        if ($decimals) {
            if ($round === 'up') {
                $value = \round($value, $decimals, PHP_ROUND_HALF_UP);
            } else if ($round === 'down') {
                $value = \round($value, $decimals, PHP_ROUND_HALF_DOWN);
            } else {
                $value = \round($value, $decimals);
            }
        } else if ($round) {
            if ($round === 'up') {
                $value = \ceil($value);
            } else if ($round === 'down') {
                $value = \floor($value);
            } else {
                $value = \round($value);
            }
        }

        return $decimals || $thousands ?
            \number_format($value, $decimals, $decimal, $thousands) :
            $value;
    }

    private function parse(string $string, $input = FALSE, bool $array = FALSE) {
        if ($string) {
            $string = $this->_parent->subparse($string);
        }

        if ($input !== FALSE) {
            $string = $input.$string;
        }

        if ($array) {
            return \array_map(function($value) {
                return $this->parse($value);
            }, \explode(',', $string));
        }

        $string = \preg_replace('/[^0-9\.\+-\*\/%\(\)]/', '', $string);

		if (\is_numeric($string)) {
			return $string;
        }

        while (\preg_match('/\(([^\(\)]+)\)/', $string, $match)) {
            $string = \preg_replace('/\(([^\(\)]+)\)/', static::compute($match[1]), $string, 1);
        }

        $string = \preg_replace('[\(\)]', '', $string);

        return static::compute($string);
    }

    public function math($string, $input, $options) {
        return static::format(
            $this->parse($string, $input),
            $options
        );
    }

    public function random($string, $input, $options) {
        $values = $this->parse($string, $input, TRUE);

        $max = \array_pop($values);
        $min = empty($values) ? 0 : \array_pop($values);

        $value = $min + \mt_rand() / \mt_getrandmax() * ($max - $min);

        return static::format($value, $options);
    }

    public function acos($string, $input, $options) {
        return static::format(
            \acos(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function acosh($string, $input, $options) {
        return static::format(
            \acosh(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function asin($string, $input, $options) {
        return static::format(
            \asin(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function atan($string, $input, $options) {
        return static::format(
            \atan(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function atan2($string, $input, $options) {
        $values = $this->parse($string, $input, TRUE);

        if (\count($values) !== 2) {
            return 'E';
        }

        return static::format(
            \atan2($values[0], $values[1]),
            $options
        );
    }

    public function atanh($string, $input, $options) {
        return static::format(
            \atanh(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function bindec($string, $input, $options) {
        return static::format(
            \bindec(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function cos($string, $input, $options) {
        return static::format(
            \cos(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function cosh($string, $input, $options) {
        return static::format(
            \cosh(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function decbin($string, $input, $options) {
        return static::format(
            \decbin(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function decoct($string, $input, $options) {
        return static::format(
            \decoct(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function degrad($string, $input, $options) {
        return static::format(
            \deg2rad(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function exp($string, $input, $options) {
        return static::format(
            \exp(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function expm1($string, $input, $options) {
        return static::format(
            \expm1(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function fmod($string, $input, $options) {
        $values = $this->parse($string, $input, TRUE);

        if (\count($values) !== 2) {
            return 'E';
        }

        return static::format(
            \fmod($values[0], $values[1]),
            $options
        );
    }

    public function hypot($string, $input, $options) {
        $values = $this->parse($string, $input, TRUE);

        if (\count($values) !== 2) {
            return 'E';
        }

        return static::format(
            \hypot($values[0], $values[1]),
            $options
        );
    }

    public function intdiv($string, $input, $options) {
        $values = $this->parse($string, $input, TRUE);

        if (\count($values) !== 2) {
            return 'E';
        }

        return static::format(
            \intdiv($values[0], $values[1]),
            $options
        );
    }

    public function log($string, $input, $options) {
        return static::format(
            \log(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function log1p($string, $input, $options) {
        return static::format(
            \log1p(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function log10($string, $input, $options) {
        return static::format(
            \log10(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function octdec($string, $input, $options) {
        return static::format(
            \octdec(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function pi($string, $input, $options) {
        return static::format(\pi(), $options);
    }

    public function raddeg($string, $input, $options) {
        return static::format(
            \raddeg(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function sin($string, $input, $options) {
        return static::format(
            \sin(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function sinh($string, $input, $options) {
        return static::format(
            \sinh(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function sqrt($string, $input, $options) {
        return static::format(
            \sqrt(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function tan($string, $input, $options) {
        return static::format(
            \tan(
                $this->parse($string, $input)
            ),
            $options
        );
    }

    public function tanh($string, $input, $options) {
        return static::format(
            \tanh(
                $this->parse($string, $input)
            ),
            $options
        );
    }

}