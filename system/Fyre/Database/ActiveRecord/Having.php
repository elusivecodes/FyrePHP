<?php

namespace Fyre\Database\ActiveRecord;

use function
    is_array;

trait Having
{

    public function having($field, $value = false)
    {
        return $this->addHaving($field, $value);
    }

    public function orHaving($field, $value = false)
    {
        return $this->addHaving($field, $value, true);
    }

    protected function addHaving($field, $value = false, bool $or = false)
    {
        if ( ! $field) {
            return $this;
        }

        if (is_array($field)) {
            foreach ($field AS $key => $val) {
                $this->addHaving($key, $val, $or, $not);
            }
            return $this;
        }

        $this->_active['having'][] = [
            'field' => $field,
            'value' => $value
        ];

        return $this;
    }

}
