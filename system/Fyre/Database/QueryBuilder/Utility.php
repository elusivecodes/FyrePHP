<?php

namespace Fyre\Database\QueryBuilder;

use function
    array_map,
    implode,
    strtoupper;

trait Utility
{

    public function buildGroupBy(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        return "\r\nGROUP BY ". implode(
            ', ',
            array_map(
                function($data) {
                    return $data['escape'] ?
                        $this->protectString($data['field']) :
                        $data['field'];
                },
                $data
            )
        );
    }

    public function buildOrderBy(array $data): string
    {
        if (empty($data)) {
            return '';
        }

        return "\r\nORDER BY ". implode(', ',
            array_map(
                function($data) {
                    return $data['escape'] ?
                        $this->protectString($data['field']).' '.strtoupper($data['dir']) :
                        $data['field'];
                },
                $data
            )
        );
    }

    public function buildLimit(int $limit, int $offset): string
    {
        if ( ! $limit && ! $offset) {
            return '';
        }

        return "\r\nLIMIT ".$this->_active['limit'].
            ($this->_active['offset'] ? ', '.$this->_active['offset'] : '');
    }

    public function buildFor(string $for): string
    {
        if ( ! $for) {
            return '';
        }

        return "\r\nFOR ".strtoupper($for);
    }

}
