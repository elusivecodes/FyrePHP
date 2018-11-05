<?php

namespace Fyre\Component\Validation;

use
    Config\Services;

use const
    FILTER_VALIDATE_EMAIL,
    FILTER_VALIDATE_IP,
    FILTER_VALIDATE_INT,
    FILTER_VALIDATE_FLOAT,
    FILTER_VALIDATE_URL;

use function
    base64_decode,
    base64_encode,
    ctype_alnum,
    ctype_alpha,
    ctype_digit,
    explode,
    filter_var,
    in_array,
    is_array,
    is_numeric,
    mb_strlen,
    preg_match,
    trim;

trait Rules
{

    public function required($value): bool
    {
        return is_array($value) ?
            ! empty($value) :
            (bool) trim($value);
    }

    public function in($value, string $list): bool
    {
        return in_array(
            $value,
            array_map(
                'trim',
                explode(
                    ',',
                    $list
                )
            )
        );
    }

    public function matches($value, string $field): bool
    {
        return $value == Services::request()->post($field);
    }

    public function differs($value, string $field): bool
    {
        return $value != Services::request()->post($field);
    }

    public function regexMatch($value, string $regex): bool
    {
        return (bool) preg_match($regex, $value);
    }

    public function minLength($value, string $min): bool
    {
        return mb_strlen($value) >= (int) $min;
    }

    public function maxLength($value, string $max): bool
    {
        return mb_strlen($value) <= (int) $max;
    }

    public function exactLength($value, string $length): bool
    {
        return mb_strlen($value) === (int) $length;
    }

    public function url($value): bool
    {
        return ! $value || filter_var($value, FILTER_VALIDATE_URL);
    }

    public function email($value): bool
    {
        return ! $value || filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function ip($value): bool
    {
        return ! $value || filter_var($value, FILTER_VALIDATE_IP);
    }

    public function base64($value): bool
    {
		return base64_encode(
            base64_decode($value)
        ) === $value;
	}

    public function alpha($value): bool
    {
        return ! $value || ctype_alpha($value);
    }

    public function alphaNumeric($value): bool
    {
        return ! $value || ctype_alnum($value);
    }

    public function alphaSpaces($value): bool
    {
        return (bool) preg_match('/^[A-Z0-9 ]+$/i', $value);
    }

    public function alphaDash($value): bool
    {
        return (bool) preg_match('/^[A-Z0-9_-]+$/i', $value);
    }

    public function alphaDashSpaces($value): bool
    {
        return (bool) preg_match('/^[A-Z0-9_- ]+$/i', $value);
    }

    public function number($value): bool
    {
        return ! $value || ctype_digit($value);
    }

    public function numberNotZero($value): bool
    {
        return $value && ctype_digit($value);
    }

    public function integer($value): bool
    {
        return ! $value || filter_var($value, FILTER_VALIDATE_INT);
    }

    public function float($value): bool
    {
        return ! $value || filter_var($value, FILTER_VALIDATE_FLOAT);
    }

    public function greaterThan($value, string $min): bool
    {
        return is_numeric($value) && $value > $min;
    }

    public function greaterOrEqual($value, string $min): bool
    {
        return is_numeric($value) && $value >= $min;
    }

    public function lessThan($value, string $max): bool
    {
        return is_numeric($value) && $value < $max;
    }

    public function lessOrEqual($value, string $max): bool
    {
        return is_numeric($value) && $value <= $max;
    }

}
