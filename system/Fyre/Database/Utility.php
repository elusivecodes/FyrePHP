<?php

namespace Fyre\Database;

use function
    addcslashes,
    array_map,
    array_slice,
    explode,
    implode,
    is_array,
    is_numeric,
    preg_match,
    strlen,
    strtolower,
    substr_replace,
    trim;

trait Utility
{

    public function escape($data, $like = false)
    {
        if (is_array($data)) {
            return array_map(function($value) {
                return $this->escape($value, $like);
            }, $data);
        }

        return $this->escapeString($data, $like);
    }

    public function escapeString(string $string = null, $like = false): string
    {
        if ($string === null) {
            return 'NULL';
        }

        if (is_numeric($string)) {
            return (string) $string;
        }

        $string = $this->conn->real_escape_string($string);

        if ($like) {
            return addcslashes($string, '%_');
        }

        return '"'.$string.'"';
    }

    public function protect($data)
    {
        if (is_array($data)) {
            return array_map(function($value) {
                return $this->protect($value);
            }, $data);
        }

        return $this->protectString($data);
    }

    public function protectString(string $string): string
    {
        return implode('.', array_map(function($value) {
            return '`'.$value.'`';
        }, explode('.', $string)));
    }

    public function protectIdentifiers(string $string, bool $matchStrings = true): string
    {
        $offset = 0;

        $regex = [];

        $regex[] = '\s*(\`?)([\w\.\$\s]+)\1(?=[^\w\.\(\$]|$)';

        if ($matchStrings) {
            $regex[] = '(["\']).*?(?<!(?<!\\\)\\\)\3';
        }

        $string = trim($string);

        // match strings, numbers and fields
        while (preg_match('/'.implode('|', $regex).'/', $string, $match, PREG_OFFSET_CAPTURE, $offset)) {
            $length = strlen($match[0][0]);
            $offset = $match[0][1] + $length;

            // skip strings
            if ( ! $match[2][0]) {
                continue;
            }

            $replace = trim($match[2][0]);

            // skip numbers
            if ( ! $replace || is_numeric($replace)) {
                continue;
            }

            if (strtolower($replace) === 'null') {
                $replace = 'NULL';
            } else {
                $replace = $this->protectString($replace);
                $replaceLength = strlen($replace);
                $offset += $replaceLength - $length;
            }

            // replace identifier with protected
            $string = substr_replace($string, $replace, $match[0][1], $length);
        }

        return $string;
    }

    public function tableColumnAs(string $string): array
    {
        if (preg_match('/^(.+)\s+AS\s+([\w\.\$\s]+)$/i', $string, $match)) {
            return array_map(
                'trim',
                array_slice($match, 1)
            );
        }

        return [
            $string,
            null
        ];
    }

    public function getConditions(string $string, bool $extended = false): ?array
    {
        preg_match('/^\s*(.+?)\s*([\>\<]\=?|\!?\=)\s*(.*)$/', $string, $match);

        if ($match) {
            return array_slice($match, 1);
        }

        if ( ! $extended) {
            return [
                $string,
                '=',
                ''
            ];
        }

        if ( ! preg_match('/^\s*(.+)\s+(?:(IS(?: NOT)?) (NULL)|((?:NOT )?IN)\s+\(([^\(\)]+)\)|((?:NOT )?LIKE)\s+(.+))\s*$/i', $string, $match)) {
            return null;
        }

        if ($match[2]) {
            $offset = 0;
        } else if ($match[4]) {
            $offset = 2;
        } else {
            $offset = 4;
        }

        return [
            $match[1],
            $match[2 + $offset],
            $match[3 + $offset]
        ];
    }

}
