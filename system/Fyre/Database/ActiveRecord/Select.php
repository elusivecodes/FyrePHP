<?php

namespace Fyre\Database\ActiveRecord;

use function
    explode,
    is_string;

trait Select
{

    public function select($select, bool $escape = true)
    {
        if (is_string($select)) {
            $select = explode(',', $select);
        }

        foreach ($select AS $field) {
            $this->_active['select'][] = [
                'field' => $field,
                'escape' => $escape
            ];
        }

        return $this;
    }

    public function countAll()
    {
        $query = $this
            ->select('COUNT(*) AS count')
            ->get();

        $count = $query ?
            (int) $query->row()->count :
            0;

        $query->free();

        $this->reset();

        return $count;
    }

    public function distinct(bool $distinct = true)
    {
        $this->_active['distinct'] = $distinct;
        return $this;
    }

    public function get()
    {
        $query = $this->buildQuery();

        $this->reset();

        return $this->query($query);
    }

}
