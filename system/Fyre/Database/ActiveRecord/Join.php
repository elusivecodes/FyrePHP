<?php

namespace Fyre\Database\ActiveRecord;

use function
    explode,
    trim;

trait Join
{

    public function join(array $join)
    {
        $this->_active['join'][] = $join;

        return $this;
    }

    public function joinStart(string $type = 'left outer')
    {
        if ( ! empty($this->_group)) {
            // issue warning
        }

        $this->_join = [
            'type' => $type,
            'table' => [],
            'on' => [],
            'using' => []
        ];

        return $this;
    }

    public function joinEnd()
    {
        if (empty($this->_join['using'])) {
            unset($this->_join['using']);
        } else if (empty($this->_join['on'])) {
            unset($this->_join['on']);
        } else {
            // can not both be empty
        }

        $this->join($this->_join);
        $this->_join = false;
    
        return $this;
    }

    public function using($fields, bool $escape = true)
    {
        if ( ! $this->_join || ! empty($this->_join['on'])) {
            // issue warning
        }

        if (is_string($fields)) {
            $fields = explode(',', $fields);
        }

        foreach ($fields AS $field) {
            $this->_join['using'][] = [
                'field' => trim($field),
                'escape' => $escape
            ];
        }
    
        return $this;
    }

}
