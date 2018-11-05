<?php

namespace Fyre\Database\ActiveRecord;

trait ActiveRecord
{

    public function reset()
    {
        $this->_active = [
            'action' => 'select',
            'table' => [],
            'distinct' => false,
            'select' => [],
            'join' => [],
            'where' => [],
            'having' => [],
            'order_by' => [],
            'group_by' => [],
            'data' => [],
            'batch' => false,
            'offset' => 0,
            'limit' => 20,
            'for' => ''
        ];

        $this->_group = [];
        $this->_join = false;

        return $this;
    }

    use Group,
        Having,
        Join,
        Like,
        Modify,
        Select,
        Utility,
        Where;

}
