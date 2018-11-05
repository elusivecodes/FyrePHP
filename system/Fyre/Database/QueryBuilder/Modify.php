<?php

namespace Fyre\Database\QueryBuilder;

use function
    array_map,
    implode;

trait Modify
{

    public function buildInsert(array $tables, array $data): string
    {
        return 'INSERT INTO '.$this->buildTables($tables).
            ' ('.implode(
                ', ',
                array_map(function($insert) {
                    return $insert['escape'] ?
                        $this->protectString($insert['field']) :
                        $insert['field'];
                }, $data)
            ).')'.
            ' VALUES ('.implode(
                ', ',
                array_map(function($insert) {
                    return $insert['escape'] ?
                        $this->escapeString($insert['value']) :
                        $insert['value'];
                }, $data)
            ).')';
    }

    public function buildInsertBatch(array $tables, array $data): string
    {
        return 'INSERT INTO '.$this->buildTables($tables).
            ' ('.implode(
                ', ',
                array_map(function($insert) {
                    return $insert['escape'] ?
                        $this->protectString($insert['field']) :
                        $insert['field'];
                }, reset($data))
            ).')'.
            ' VALUES '.implode(
                ', ',
                array_map(
                    function($row) {
                        return '('.implode(
                            ', ',
                            array_map(function($insert) {
                                return $insert['escape'] ?
                                    $this->escapeString($insert['value']) :
                                    $insert['value'];
                            }, $data)
                        ).')';
                    },
                    $data
                )
            );
    }

    public function buildUpdate(array $tables, array $data): string
    {
        return 'UPDATE '.$this->buildTables($tables).
            ' SET '.implode(
                ', ',
                array_map(function($update) {
                    return $update['escape'] ?
                        $this->protectString($update['field']).' = '.$this->escapeString($update['value']) :
                        $update['field'].' = '.$update['value'];
                }, $data)
            );
    }

    public function buildDelete(array $tables): string
    {
        return 'DELETE FROM '.$this->buildTables($tables);
    }

}
