<?php

namespace Fyre\Database\Forge;

interface ForgeInterface
{

    public function addColumn(string $tableName, array $field);
    public function addField($field);
    public function addFields(array $fields);
    public function addIndex($field, string $type);
    public function createDb(string $dbName);
    public function createTable(string $tableName, bool $ifNotExists = false, ?array $attributes = null);
    public function dropColumn(string $tableName, string $field);
    public function dropDb(string $dbName);
    public function dropTable(string $tableName, bool $ifExists = false);
    public function modifyColumn(string $tableName, array $field);
    public function renameTable(string $tableName, string $newTable);

}
