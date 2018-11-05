<?php

namespace Fyre\Database\QueryBuilder;

trait QueryBuilder
{

    public function buildQuery(): string
    {
        if ($this->_active['action'] === 'insert') {
            if ($this->_active['batch']) {
                return $this->buildInsertBatch($this->_active['table'], $this->_active['data']);
            } else {
                return $this->buildInsert($this->_active['table'], $this->_active['data']);
            }
        }

        if ($this->_active['action'] === 'select') {
            $query = $this->buildSelect($this->_active['table'], $this->_active['select'], $this->_active['distinct']);
        } else if ($this->_active['action'] === 'update') {
            $query = $this->buildUpdate($this->_active['table'], $this->_active['data']);
        } else if ($this->_active['action'] === 'delete') {
            $query = $this->buildDelete($this->_active['table']);
        }

        // join
        $query .= $this->buildJoin($this->_active['join']);

        // where
        $query .= $this->buildWhere($this->_active['where']);

        // group by
        $query .= $this->buildGroupBy($this->_active['group_by']);

        // having

        // order by
        $query .= $this->buildOrderBy($this->_active['order_by']);

        // limit
        $query .= $this->buildLimit($this->_active['limit'], $this->_active['offset']);

        // for
        if ($this->_active['for']) {
            $query .= $this->buildFor($this->_active['for']);
        }

        return $query;
    }

    use Join,
        Modify,
        Select,
        Utility,
        Where;

}
