<?php

namespace Fyre\Database\ActiveRecord;

use function
    explode,
    trim;

trait Utility
{

    public function table($tables, bool $escape = true)
    {
        if (is_string($tables)) {
            $tables = explode(',', $tables);
        }

        $key = $this->_join ? '_join' : '_active';

        foreach ($tables AS $table) {
            $this->$key['table'][] = [
                'table' => trim($table),
                'escape' => $escape
            ];
        }

        return $this;
    }

    public function orderBy($fields, ?string $dir = null, bool $escape = true)
    {
        if (is_string($fields)) {
            $fields = explode(',', $fields);
        }

        foreach ($fields AS $field) {
            $this->_active['order_by'][] = [
                'field' => trim($field),
                'dir' => $dir,
                'escape' => $escape
            ];
        }

        return $this;
    }

    public function groupBy($fields, bool $escape = true)
    {
        if (is_string($fields)) {
            $fields = explode(',', $fields);
        }

        foreach ($fields AS $field) {
            $this->_active['group_by'][] = [
                'field' => trim($field),
                'escape' => $escape
            ];
        }

        return $this;
    }

    public function limit(int $limit, int $offset = 0)
    {
        $this->_active['limit'] = $limit;
        $this->_active['offset'] = $offset;

        return $this;
    }

    public function for(string $for = 'update')
    {
        if (in_array(
            strtolower($for),
            ['update', 'share']
        )) {
            $this->_active['for'] = $for;
        }

        return $this;
    }

}
