<?php

namespace Fyre\Database\QueryBuilder;

use function
    array_keys,
    array_map,
    implode,
    is_array,
    is_numeric,
    preg_match_all,
    preg_split,
    strlen,
    strtolower,

    str_begin;

trait Where
{

    public function buildHaving(): string
    {
        return "\r\nHAVING".
            implode(
                '',
                array_map(
                    function($having, $key) {
                        $prefix = "\r\n";
                        if ($key > 0) {
                            $prefix .= ($having['or'] ? 'OR' : 'AND').' ';
                        }

                        // in / not in
                        if (is_array($having['value'])) {
                            return $prefix.$this->buildWhereIn($having['field'], $having['value'], $having['not'], $table);
                        }

                        // null
                        if ($having['value'] === null) {
                            return $prefix.$this->buildWhereNull($having['field'], $having['not'], $table);
                        }

                        return $prefix.$this->buildWhereValue($having['field'], $having['value'], $having['not'], $table);
                    },
                    $data,
                    array_keys($data)
                )
            );
    }

    public function buildWhere(array $data, bool $group = false): string
    {
        if (empty($data)) {
            return '';
        }

        return $group ?
            '' : "\r\nWHERE ".
            implode(
                '',
                array_map(
                    function($where, $key) {
                        $prefix = '';
                        if ($key > 0) {
                            $prefix .= "\r\n".($where['or'] ? 'OR' : 'AND').' ';
                        }

                        array_key_exists('type', $where) || $where['type'] = 'where';

                        if (array_key_exists('where', $where)) {
                            return $prefix.($where['not'] ? ' NOT' : '').
                                '( '.$this->buildWhere($where['where'], TRUE).' )';
                        }

                        // field join
                        if (array_key_exists('column', $where)) {
                            return $prefix.$this->buildWhereColumn($where['field'], $where['column'], $where['not']);
                        }

                        // like / not like
                        if (array_key_exists('like', $where)) {
                            return $prefix.$this->buildWhereLike($where['field'], $where['like'], $where['not'], $where['insensitive'], $where['wildcard']);
                        }

                        if ($where['value'] === false) {
                            return $prefix.$this->buildWhereFull($where['field'], $where['not']);
                        }

                        // in / not in
                        if (is_array($where['value'])) {
                            return $prefix.$this->buildWhereIn($where['field'], $where['value'], $where['not']);
                        }

                        // null
                        if ($where['value'] === null) {
                            return $prefix.$this->buildWhereNull($where['field'], $where['not']);
                        }

                        return $prefix.$this->buildWhereValue($where['field'], $where['value'], $where['not']);
                    },
                    $data,
                    array_keys($data)
                )
            );
    }

    protected function buildField(string $field, ?string $table = null): string
    {
        return $this->protectIdentifiers(
            $table ?
                str_begin(
                    $field,
                    $table.'.'
                ) :
                $field,
            false
        );
    }

    protected function buildWhereLike(string $field, string $value, bool $not, bool $insensitive, string $wildcard, ?string $table = null): string
    {
        $field = $this->buildField($field, $table);

        if ($insensitive) {
            $field = 'LOWER('.$field.')';
            $value = strtolower($value);
        }
 
        return $field.
            ($not ? ' NOT' : '').
            ' LIKE '.
            '"'.
            ($wildcard === 'before' || $wildcard === 'both' ? '%' : '').
            $this->escapeString($value, true).
            ($wildcard === 'after' || $wildcard === 'both' ? '%' : '').
            '"';
    }

    protected function buildWhereNull(string $field, bool $not, ?string $table = null): string
    {
        return $this->buildField($field, $table).
            ' IS '.($not ? 'NOT ' : '').
            'NULL';
    }

    protected function buildWhereIn(string $field, array $values, bool $not, ?string $table = null): string
    {
        return $this->buildField($field, $table).
            ($not ? ' NOT' : '').
            ' IN '.
            '('.implode(', ', $this->escape($values)).')';
    }

    protected function buildWhereColumn(string $field, string $column, bool $not, ?string $table = null): string
    {
        [$a, $condition, $b] = $this->getConditions($field);
        $b || $b = $column;

        return ($not ? 'NOT ' : '').'('.$this->buildField($a, $table).
            ' '.$condition.' '.
            $this->buildField($b).')';
    }

    protected function buildWhereFull(string $string, bool $not, ?string $table = null): string
    {
        preg_match_all('/(.+?)(?:\s+(AND|OR)\s+|$)/i', $string, $matches, PREG_SET_ORDER);

        $result = '';
        foreach ($matches AS $match) {
            [$a, $condition, $b] = $this->getConditions($match[1], true);

            // null
            if ($condition === 'IS' || $condition === 'IS NOT') {
                $result .= $this->buildWhereNull($a, $condition === 'IS NOT', $table);

            // in
            } else if ($condition === 'IN' || $condition === 'NOT IN') {
                $v = preg_split('/,\s*/', $b);
                $result .= $this->buildWhereIn($a, $v, $condition === 'NOT IN', $table);

            // like
            } else if ($condition === 'LIKE' || $condition === 'NOT LIKE') {
                $length = strlen($b) - 1;
                if ($b[0] === '%' && $b[$length] !== '%') {
                    $wildcard = 'before';
                } else if ($b[0] !== '%' && $b[$length] === '%') {
                    $wildcard = 'after';
                } else {
                    $wildcard = 'both';
                }

                $result .= $this->buildWhereLike($a, $b, $condition === 'NOT LIKE', false, $wildcard, $table);

            // default
            } else {
                if ( ! is_numeric($b)) {
                    $b = $this->escapeString($b);
                }

                $result .= $this->buildField($a, $table).' '.$condition.' '.$b;
            }

            array_key_exists(2, $match) && $result .= ' '.$match[2];
        }

        return ($not ? 'NOT ' : '').'('.$result.')';
    }

    protected function buildWhereValue(string $field, string $value, bool $not, ?string $table = null): string
    {
        [$a, $condition, $b] = $this->getConditions($field);
        $b || $b = $value;

        if ( ! is_numeric($b)) {
            $b = $this->escapeString($b);
        }

        return ($not ? 'NOT ' : '').'('.$this->buildField($a, $table).
            ' '.$condition.' '.
            $b.')';
    }

}
