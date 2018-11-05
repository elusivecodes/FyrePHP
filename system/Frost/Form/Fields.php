<?php

namespace Frost\Form;

use function
    is_array,
    is_int,

    str_escape;

trait Fields
{

    public static function hidden($name, $value = '', bool $deep = false): string
    {
        $html = $deep ? '
    ' : '';

        if (is_array($name)) {
            foreach ($name AS $key => $val) {
                $html .= self::hidden($key, $val, true);
            }
        } else if (is_array($value)) {
            foreach ($value AS $key => $val) {
                ! is_int($key) || $key = '';
                $html .= self::hidden($name.'['.$key.']', $val, true);
            }
        } else {
            $html .= self::input(
                self::arrayFromData($name, 'hidden'),
                str_escape($value)
            );
        }

        return $html;
    }

    public static function input($data = '', $value = '', string $type = 'text'): string
    {
        return '<input'.
            self::attributeString(
                self::arrayFromData(
                    $data,
                    $type,
                    [
                        'value' => $value
                    ]
                )
            ).' />';
    }

    public static function password($data = '', $value = ''): string
    {
        return self::input($data, $value, 'password');
    }

    public static function upload($data = '', $value = ''): string
    {
        return self::input($data, $value, 'file');
    }

    public static function textarea($data = '', ?string $value = ''): string
    {
        return '<textarea '.
            self::attributeString(
                self::arrayFromData(
                    $data,
                    false,
                    [
                        'cols' => '40',
                        'rows' => '10'
                    ]
                )
            ).'>'.
            str_escape($value).
            '</textarea>';
    }

    public static function dropdown($data = '', array $options = [], $selected = []): string
    {
        $data = self::arrayFromData($data);

        if (isset($data['selected'])) {
            $selected = $data['selected'];
            unset($data['selected']);
        }

        if (isset($data['options'])) {
            $options = $data['options'];
            unset($data['options']);
        }

        if (empty($selected)) {
            // get selected
        }

        $html = '<select '.self::attributeString($data).'>';

        foreach ($options AS $key => $val) {
            // add options
        }

        return $html .= '</select>';
    }

    public static function multiselect($data = '', array $options = [], $selected = []): string
    {
        if ( ! isset($data['multiple'])) {
            $data['multiple'] = 'multiple';
        }

        return self::dropdown($data, $options, $selected);
    }

    public static function checkbox($data = '', $value = '', bool $checked = false): string
    {
        return self::input(
            self::_arrayFromData(
                $data,
                'checkbox',
                [
                    'value' => $value,
                    'checked' => $checked
                ]
            )
        );
    }
    
    public static function radio($data = '', $value = '', bool $checked = false): string
    {
        return self::input(
            self::_arrayFromData(
                $data,
                'radio',
                [
                    'value' => $value,
                    'checked' => $checked
                ]
            )
        );
    }

}
