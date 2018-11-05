<?php

namespace Fyre\Database\Forge;

use
    Config\Services;

use function
    array_key_exists;

class Forge
{
    protected $db;
    protected $fields = [];
    protected $keys = [];

    public function __construct(&$db = null)
    {
        $this->db = Services::database();
    }

    public function addColumn(string $tableName, string $field, array $data)
    {
        $query = 'ALTER TABLE '.
            $this->db->protectString($tableName).
            ' ADD '.
            $this->prepareField($field, $data);

        //$this->db->query($query);
    }

    public function addField(string $field, array $data)
    {
        $this->fields[$field] = $data;

        return $this;
    }

    public function addFields(array $fields)
    {
        $this->fields += $fields;

        return $this;
    }

    public function addForeignKey(string $keyName, string $tableName, string $referenceTable, array $columns, string $onDelete = 'cascade', string $onUpdate = 'cascade')
    {
        $query = 'ALTER TABLE '.
            $this->db->protectString($tableName).
            ' ADD CONSTRAINT '.
            $this->db->protectString($keyName).
            ' FOREIGN KEY '.
            $this->prepareIndex(array_keys($columns)).
            ' REFERENCES '.
            $this->prepareIndex($columns, $referenceTable).
            ' ON DELETE '.
            strtoupper($onDelete).
            ' ON UPDATE '.
            strtoupper($onUpdate);

         $this->db->query($query);
    }

    public function addIndex($field, string $type = 'index', ?string $name = null)
    {
        $this->keys[] = [
            'field' => $field,
            'type' => $type,
            'name' => $name
        ];

        return $this;
    }

    public function createDb(string $dbName, bool $ifNotExists = false, ?array $attributes = null)
    {
        $query =  'CREATE DATABASE '.
            ($ifNotExists ?
                'IF NOT EXISTS ' :
                ''
            ).
            $this->db->protectString($dbName);

        foreach ($attributes AS $attribute => $val) {
            $query .= ' '.$attribute.' = '.$val;
        }

        //return $this->db->query($query);
    }

    public function createTable(string $tableName, bool $ifNotExists = false, ?array $attributes = null)
    {
        $query = 'CREATE TABLE '.
            ($ifNotExists ?
                'IF NOT EXISTS ' :
                ''
            ).
            $this->db->protectString($tableName);

        // fields
        $query .= '('.$this->prepareFields($this->fields, $this->keys).')';

        if (array_key_exists('engine', $attributes)) {
            $query .= ' ENGINE = '.$attributes['engine'];
        }

        if (array_key_exists('collation', $attributes)) {
            $query .= ' CHARSET='.str_before($attributes['collation'], '_').' COLLATE '.$attributes['collation'];
        }

        $this->fields = [];
        $this->keys = [];

        return $this->db->query($query);
    }

    public function dropColumn(string $tableName, string $field)
    {

    }

    public function dropDb(string $dbName, bool $ifExists = false)
    {
        $query = 'DROP DATABASE '.
            ($ifExists ?
                'IF EXISTS ' :
                ''
            ).
            $dbName;

        //return $this->db->query($query);
    }

    public function dropTable(string $tableName, bool $ifExists = false)
    {
        $query = 'DROP TABLE '.
            ($ifExists ?
                'IF EXISTS ' :
                ''
            ).
            $this->db->protectString($tableName);

        //return $this->db->query($query);
    }

    public function modifyColumn(string $tableName, array $fields)
    {
        $query = 'ALTER TABLE '.
            $this->db->protectString($tableName).
            ' CHANGE '.
            $this->prepareFields($fields, null, true);

        //$this->db->query($query);
    }

    public function renameTable(string $tableName, string $newTable)
    {
        $query = 'ALTER TABLE '.
            $this->db->protectString($tableName).
            ' RENAME TO '.
            $this->db->protectString($newTable);

        //return $this->db->query($query);
    }

    protected function prepareFields(array $fields, array $keys)
    {
        return implode(
            ' , ',
            array_map(
                function($key, $data) {
                    return $this->prepareField($key, $data);
                },
                array_keys($fields),
                $fields
            )
        ).
        ( ! empty($fields) && ! empty($keys) ?
            ' , ' :
            ''
        ).
        implode(
            ' , ',
            array_map(
                function($data) {
                    return ($data['type'] === 'primary' ?
                        'PRIMARY KEY' :
                        strtoupper($data['type'])
                    ).
                    ' '.
                    $this->prepareIndex($data['field'], $data['name']);
                },
                $keys
            )
        );
    }

    protected function prepareField($key, $data)
    {
        if (array_key_exists('collation', $data)) {
            $data['charset'] = str_before($data['collation'], '_');
        }
        return $this->db->protectString($key).
        ' '.
        $data['type'].
        (array_key_exists('constraint', $data) ?
            '('.$data['constraint'].')' :
            ''
        ).
        (array_key_exists('unsigned', $data) && $data['unsigned'] ?
            ' UNSIGNED' :
            ''
        ).
        (array_key_exists('charset', $data) ?
            ' CHARACTER SET '.$data['charset'] :
            ''
        ).
        (array_key_exists('collation', $data) ?
            ' COLLATE '.$data['collation'] :
            ''
        ).
        (array_key_exists('null', $data) && $data['null'] ?
            ' NULL' : 
            ' NOT NULL'
        ).
        (array_key_exists('default', $data) ?
            ' DEFAULT '.$this->db->escapeString($data['default']) :
            ''
        ).
        (array_key_exists('autoInc', $data) && $data['autoInc'] ?
            ' AUTO_INCREMENT' :
            ''
        ).
        (array_key_exists('comment', $data) ?
            ' COMMENT '.$this->db->escapeString($data['comment']) :
            ''
        );
    }

    protected function prepareIndex($data, ?string $name = null)
    {
        return
            ($name ?
                $this->db->protectString($name) :
                ''
            ).
            '('.
            implode(
                ', ',
                array_map(
                    function($field) {
                        return $this->db->protectString($field);
                    },
                    is_array($data) ?
                        $data :
                        [$data]
                )
            ).
            ')';
    }

}
