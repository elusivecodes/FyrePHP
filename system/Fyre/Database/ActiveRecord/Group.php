<?php

namespace Fyre\Database\ActiveRecord;

use function
    array_pop,
    count;

trait Group
{

    public function groupStart()
    {
        return $this->addGroup();
    }

    public function orGroupStart()
    {
        return $this->addGroup(true);
    }

    public function notGroupStart()
    {
        return $this->addGroup(false, true);
    }

    public function orNotGroupStart()
    {
        return $this->addGroup(true, true);
    }

    public function groupEnd()
    {
        return $this->closeGroup();
    }

    protected function addGroup(bool $or = false, bool $not = false)
    {
        $this->_group[] = [
            'or' => $or,
            'not' => $not,
            'where' => []
        ];

        return $this;
    }

    protected function closeGroup()
    {
        return $this->addToGroup(
            array_pop($this->_group)
        );
    }

    protected function addToGroup(array $data)
    {
        $group_count = count($this->_group);
        if ($group_count) {
            $this->_group[$group_count - 1]['where'][] = $data;
        } else if ($this->_join) {
            if ( ! empty($this->_join['using'])) {
                // issue warning
            }

            $this->_join['on'][] = $data;
        } else {
            $this->_active['where'][] = $data;
        }

        return $this;
    }

}
