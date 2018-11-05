<?php

if ( ! function_exists('byte_format')) {
    function byte_format($bytes, string $unit = null, int $decimals = 2, string $decimal = '.', string $thousands = ','): string
    {
        static $units;

        if ( ! $units) {
            Config\Services::lang()->load('number');

            $units = [
                'B' => 0,
                'KB' => 1,
                'MB' => 2,
                'GB' => 3,
                'TB' => 4,
                'PB' => 5,
                'EB' => 6,
                'ZB' => 7,
                'YB' => 8
            ];
        }

        $value = abs($bytes);

        if ( ! $unit || ! isset($units[$unit])) {
            $unit = array_search(
                floor(
                    log($value) / log(1024)
                ),
                $units
            );
        }

        $value = $value / pow(
            1024,
            floor($units[$unit])
        );

        return
            number_format(
                $value,
                $decimals,
                $decimal,
                $thousands
            ).
            ' '.
            lang($unit);
    }
}

if ( ! function_exists('clamp')) {
    function clamp(float $value, float $min = 0, float $max = 1): float
    {
        return max($min, min($max, $value));
    }
}

if ( ! function_exists('lerp')) {
    function lerp(float $a, float $b, float $amount): float
    {
        return $a * (1 - $amount) + $b * $amount;
    }
}

if ( ! function_exists('map')) {
    function map($value, $aMin, $aMax, $bMin, $bmax)
    {
        return ($value - $amin) * ($bMax - $bmin) / ($aMax - $aMin) + $bMin;
    }
}