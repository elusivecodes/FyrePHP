<?php

namespace Frost\Form;

use function
    array_key_exists,
    is_array,
    ltrim;

trait Utility
{

    protected static function arrayFromData($data, ?string $type = null, array $defaults = [], string $key = 'name'): array
    {
        if ( ! is_array($data)) {
            $data = [$key => $data];
        }

        if ($type && ! array_key_exists('type', $data)) {
            $data['type'] = $type;
        }

        return $data + $defaults;
    }

    protected static function attributeString($attributes): string
    {
        if ( ! $attributes) {
            return '';
        }

        $html = '';
        if (is_array($attributes)) {
            foreach ($attributes AS $key => $val) {
                $html .= ' '.$key.'="'.$val.'"';
            }
        } else {
            $html = $attributes;
        }

        return ' '.ltrim($html);
    }

}
