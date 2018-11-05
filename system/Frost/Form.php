<?php

namespace Frost;

use
    Config\Services,
    Frost\Form\Fields,
    Frost\Form\Utility;

use function
    strpos,
    strtolower,

    site_url;

abstract class Form
{

    use Fields,
        Utility;

    public static function open(string $action = '', array $attributes = [], array $hidden = []): string
    {
        if ( ! $action) {
            $action = site_url(
                Services::request()->uriString()
            );
        } else if (strpos($action, '//') === false) {
            $action = site_url($action);
        }

        if ( ! isset($attributes['method'])) {
            $attributes['method'] = 'post';
        }

        if ( ! isset($attributes['accept-charset'])) {
            $attributes['accept-charset'] = strtolower(
                Services::response()->charset()
            );
        }

        $hidden[Services::security()->csrfToken()] = Services::security()->csrfHash();

        return '<form action="'.$action.'"'.static::attributeString($attributes).'>'.static::hidden($hidden).'';
    }

    public static function openMultipart(string $action = '', array $attributes = [], array $hidden = []): string
    {
        if ( ! isset($attributes['enctype'])) {
            $attributes['enctype'] = 'multipart/form-data';
        }

        return static::open(
            $action,
            $attributes,
            $hidden
        );
    }

    public static function close(): string
    {
        return '</form>';
    }

    public static function fieldset(string $legend = '', array $attributes = []): string
    {
        return '<fieldset'.static::attributeString($attributes).'>'.
            ($legend ? '<legend>'.$legend.'</legend>' : '');
    }

    public static function fieldsetClose(): string
    {
        return '</fieldset>';
    }

    public static function submit($data = '', $value = ''): string
    {
        return static::input(
            static::arrayFromData(
                $data,
                'submit'
            ),
            $value
        );
    }

    public static function reset($data = '', $value = ''): string
    {
        return static::input(
            static::arrayFromData(
                $data,
                'reset'
            ),
            $value
        );
    }

    public static function button($data = '', $value = ''): string
    {
        $data = static::arrayFromData($data, 'button');

        if (isset($data['value'])) {
            $value = $data['value'];
            unset($data['value']);
        }

        return '<button '.static::attributeString($data).'>'.$value.'</button>'."\n";
    }
    
    public static function label($data = '', $value = ''): string
    {
        $data = static::arrayFromData($data, null, [], 'for');

        if (isset($data['value'])) {
            $value = $data['value'];
            unset($data['value']);
        }

        return '<label '.static::attributeString($data).'>'.$value.'</label>';
    }

}
