<?php

namespace Fyre\Database\ActiveRecord;

trait Modify
{

    public function decrement(string $field, int $amount = 1)
    {
        $field = $this->protectString($field);

        return $this->set(
            [
                $field => $field.' - '.$amount
            ],
            false
        );
    }

    public function increment(string $field, int $amount = 1)
    {
        $field = $this->protectString($field);

        return $this->set(
            [
                $field => $field.' + '.$amount
            ],
            false
        );
    }

    public function set($data, $value = '', bool $escape = true)
    {
        if ( ! is_array($data)) {
            $data = [$data => $value];
        }

        foreach ($data AS $field => $value){
            $this->_active['data'][] = [
                'field' => $field,
                'value' => $value,
                'escape' => $escape
            ];
        }

        return $this;
    }

    public function insert()
    {
        $this->_active['action'] = 'insert';

        $query = $this->buildQuery();

        $this->reset();

        return $this->query($query);
    }

    public function insertBatch()
    {
        $this->_active['batch'] = true;

        return $this->insert();
    }

    public function update()
    {
        $this->_active['action'] = 'update';

        $query = $this->buildQuery();

        $this->reset();

        return $this->query($query);
    }

    public function delete()
    {
        $this->_active['action'] = 'delete';

        $query = $this->buildQuery();

        $this->reset();

        return $this->query($query);
    }

}
