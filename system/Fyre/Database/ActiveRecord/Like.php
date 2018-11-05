<?php

namespace Fyre\Database\ActiveRecord;

use function
    is_array;

trait Like
{

    public function like($field, $value = false, bool $insensitive = true, $wildcard = 'both')
    {
        return $this->addLike($field, $value, false, false, $wildcard);
    }

    public function orLike($field, $value = false, bool $insensitive = true, $wildcard = 'both')
    {
        return $this->addLike($field, $value, true, false, $wildcard);
    }

    public function notLike($field, $value = false, bool $insensitive = true, $wildcard = 'both')
    {
        return $this->addLike($field, $value, false, true, $wildcard);
    }

    public function orNotLike($field, $value = false, bool $insensitive = true, $wildcard = 'both')
    {
        return $this->addLike($field, $value, true, true, $wildcard);
    }

    protected function addLike($field, $value = false, bool $or = false, bool $not = false, bool $insensitive = true, string $wildcard = null)
    {
        if ( ! $field) {
            return $this;
        }

        if (is_array($field)) {
            foreach ($field AS $key => $val) {
                $this->addLike($key, $val, $or, $not, $insensitive, $wildcard);
            }
            return $this;
        }

        if ( ! $value) {
            return $this;
        }

        if (is_array($value)) {
            $i = 0;
            $this->add_group($or);
            foreach ($value AS $val) {
                $this->addLike($field, $val, ($i > 0), $not, $insensitive, $wildcard);
                $i++;
            }
            return $this->closeGroup();
        }

        return $this->addToGroup(
            [
                'field' => $field,
                'like' => $value,
                'or' => $or,
                'not' => $not,
                'insensitive' => $insensitive,
                'wildcard' => $wildcard
            ]
        );
    }

}
