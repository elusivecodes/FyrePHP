<?php

namespace Fyre\Database\QueryBuilder;

use function
    array_map,
    implode;

trait Select
{

    public function buildSelect(array $tables, array $data, bool $distinct): string
    {
        return 'SELECT '.
            ($distinct ? 'DISTINCT ' : '').
            implode(
                ', ',
                array_map(function($data) {
                    if ( ! $data['escape']) {
                        return $data['field'];
                    }

                    [$field, $fieldAs] = $this->tableColumnAs($data['field']);

                    return $this->protectIdentifiers($field).
                        ($fieldAs ?
                            ' AS '.$this->protectString($fieldAs) :
                            ''
                        );
                }, $data)
            ).
            ( ! empty($tables) ?
                "\r\n".
                'FROM '.$this->buildTables($tables) :
                ''
            );
    }

    protected function buildTables(array $tables): string
    {
        return implode(
            ', ',
            array_map(function($data) {
                if ( ! $data['escape']) {
                    return $data['table'];
                }

                [$table, $tableAs] = $this->tableColumnAs($data['table']);

                return $this->protectIdentifiers($table).
                    ($tableAs ?
                        ' AS '.$this->protectString($tableAs) :
                        ''
                    );
            },
            $tables)
        );
    }

}
