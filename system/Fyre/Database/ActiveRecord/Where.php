<?php

namespace Fyre\Database\ActiveRecord;

use function
    is_array;

trait Where
{

    public function column($field, $column, bool $escape = true)
    {
        return $this->addWhere($field, $column, $escape, 'column', false, false);
    }

    public function orColumn($field, $column, bool $escape = true)
    {
        return $this->addWhere($field, $column, $escape, 'column', true, false);
    }

    public function columnNot($field, $column, bool $escape = true)
    {
        return $this->addWhere($field, $column, $escape, 'column', false, true);
    }

    public function orColumnNot($field, $column, bool $escape = true)
    {
        return $this->addWhere($field, $column, $escape, 'column', true, true);
    }

    public function where($field, $value = false, bool $escape = true)
    {
        return $this->addWhere($field, $value, $escape, 'value', false, false);
    }

    public function orWhere($field, $value = false, bool $escape = true)
    {
        return $this->addWhere($field, $value, $escape, 'value', true, false);
    }

    public function whereNot($field, $value = false, bool $escape = true)
    {
        return $this->addWhere($field, $value, $escape, 'value', false, true);
    }

    public function orWhereNot($field, $value = false, bool $escape = true)
    {
        return $this->addWhere($field, $value, $escape, 'value', true, true);
    }

    protected function addWhere($field, $value = false, bool $escape = true, string $type = 'value', bool $or = false, bool $not = false)
    {
        if ( ! $field) {
            return $this;
        }

        if (is_array($field)) {
            foreach ($field AS $key => $val) {
                $this->addWhere($key, $val, $escape, $type, $or, $not);
            }
            return $this;
        }

        return $this->addToGroup(
            [
                'field' => $field,
                $type => $value,
                'or' => $or,
                'not' => $not
            ]
        );
    }

}
