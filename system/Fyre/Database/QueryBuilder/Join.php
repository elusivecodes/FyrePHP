<?php

namespace Fyre\Database\QueryBuilder;

use function
    array_keys,
    array_key_exists,
    array_map,
    implode,
    strtoupper;

trait Join
{

    public function buildJoin($join): string
    {
        if (empty($join)) {
            return '';
        }

        return "\r\n".implode("\r\n",
            array_map(function($data) {
                return (array_key_exists('type', $data) ?
                    strtoupper($data['type']).' ' :
                    'LEFT OUTER '
                ).'JOIN '.
                (is_string($data['table']) ?
                    $this->protectString($data['table']) :
                    ($data['table']['escape'] ?
                        $this->protectString($data['table']['table']) :
                        $data['table']['table']
                    )
                ).
                (array_key_exists('table_as', $data) ?
                    ' AS '.$this->protectString($data['table_as']) :
                    ''
                ).
                (array_key_exists('using', $data) ?
                    $this->buildJoinUsing($data['using']) :
                    $this->buildJoinOn(
                        $data['on'],
                        array_key_exists('table_as', $data) ?
                            $data['table_as'] :
                            $data['table']
                    )
                );
            }, $join)
        );
    }

    protected function buildJoinOn(array $data, string $table, bool $group = false): string
    {
        return $group ?
            '' : ' ON '.
            implode(
                '',
                array_map(
                    function($where, $key) use ($table) {
                        array_key_exists('not', $where) || $where['not'] = false;

                        // and / or
                        $prefix = '';
                        if ($key > 0) {
                            $prefix .= ' '.(array_key_exists('or', $where) && $where['or'] ? 'OR' : 'AND').' ';
                        }

                        // nested group
                        if (array_key_exists('where', $where)) {
                            return $prefix.($where['not'] ? 'NOT ' : '').
                                '( '.$this->buildJoinOn($where['where'], $table, true).' )';
                        }

                        // field join
                        if (array_key_exists('column', $where)) {
                            return $prefix.$this->buildWhereColumn($where['field'], $where['column'], $where['not'], $table);
                        }

                        if ($where['value'] === false) {
                            
                        }

                        // in / not in
                        if (is_array($where['value'])) {
                            return $prefix.$this->buildWhereIn($where['field'], $where['value'], $where['not'], $table);
                        }

                        // like / not like
                        if (array_key_exists('like', $where)) {
                            array_key_exists('insenitive', $where) || $where['insensitive'] = true;
                            array_key_exists('wildcard', $where) || $where['wildcard'] = 'both';
                            return $prefix.$this->buildWhereLike($where['field'], $where['like'], $where['not'], $where['insensitive'], $where['wildcard'], $table);
                        }

                        // null
                        if ($where['value'] === null) {
                            return $prefix.$this->buildWhereNull($where['field'], $where['not'], $table);
                        }

                        return $prefix.$this->buildWhereValue($where['field'], $where['value'], $where['not'], $table);
                    },
                    $data,
                    array_keys($data)
                )
            );
    }

    protected function buildJoinUsing(array $data): string
    {
        return ' USING ('.
            implode(
                ', ',
                array_map(function($data) {
                    if ( ! $data['escape']) {
                        return $data['field'];
                    }

                    return $this->protectString($data['field']);
                },
                $data)
            ).
            ')';
    }

}
