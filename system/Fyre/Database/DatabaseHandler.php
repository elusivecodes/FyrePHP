<?php

namespace Fyre\Database;

use
    Config\Services;

abstract class DatabaseHandler
{
    protected $config;
    protected $conn;
    protected $lastQuery;

    public function __construct(DatabaseConfig &$config)
    {
        $this->config = &$config;

        $this->reset();
        $this->connect();

        Services::logger()->debug('Database class loaded');
    }

    public function lastQuery(): ?string
    {
        return $this->lastQuery;
    }

    public function query(string $query)
    {
        $this->lastQuery = $query;

        Services::benchmark()->addQuery($query);

        $result = $this->_query($query);

        if ( ! $result) {
            if ($this->transCount > 0) {
                $this->transStatus = false;
            }

            throw new \Exception($this->conn->error);
        }

        return $result;
    }

    use ActiveRecord\ActiveRecord,
        QueryBuilder\QueryBuilder,
        Transactions,
        Utility;

}
