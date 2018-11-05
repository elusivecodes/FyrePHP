<?php

namespace Fyre\Database\Handlers;

use
    Fyre\Database\DatabaseHandler;

use function
    is_bool,
    md5,
    mysqli_init;

class MySQLi extends DatabaseHandler
{

    public function affectedRows()
    {
        return $this->conn->affected_rows;
    }

    public function close()
    {
        $this->conn->close();
    }

    public function connect()
    {
        $this->conn = mysqli_init();

        if ($this->config->ssl) {
            $this->conn->ssl_set(
                $this->config->ssl['key'],
                $this->config->ssl['cert'],
                $this->config->ssl['ca'],
                $this->config->ssl['capath'],
                $this->config->ssl['cipher']
            );
        }

        if ($this->config->collation) {
            $this->conn->options(MYSQLI_INIT_COMMAND, 'SET collation_connection = '.$this->config->collation);
        }

        if ( ! $this->conn->real_connect(
            ($this->config->persistent ?
                'p:' :
                ''
            ).$this->config->hostname,
            $this->config->username,
            $this->config->password,
            $this->config->database,
            (int) $this->config->port,
            '',
            $this->config->compress ?
                MYSQLI_CLIENT_COMPRESS :
                0
        )) {
            echo 'Failed to connect to database';
            exit;
        }

        if ($this->config->charset) {
            $this->conn->set_charset($this->config->charset);
        }
    }

    public function insertId()
    {
        return $this->conn->insert_id;
    }

    public function _query(string $query, ?array $bindings = null)
    {
        $result = $bindings ?
            $this->_prepare($query, $bindings) :
            $this->conn->query($query);

        return is_bool($result) ?
            $result :
            new MySQLiResult($result);
    }

    public function _prepare(string $query, array $bindings)
    {
        $types = '';
        foreach ($bindings AS $bind) {
            if (ctype_digit($bind)) {
                $types .= 'i';
            } else if (filter_var($bind, FILTER_VALIDATE_FLOAT)) {
                $types .= 'd';
            } else if (is_string($bind)) {
                $types .= 's';
            } else {
                $types .= 'b';
            }
        }

        $statement = $this->conn->prepare($query);

        $statement->bind_param($types, ...$bindings);

        // $statement->send_long_data($index, $data);

        $statement->execute();

        return $statement->get_result();
    }

    public function getLock(string $name, int $time = 300)
    {
        $query = $this
            ->select('GET_LOCK(\''.md5($name).'\', '.$time.') AS lock')
            ->get();

        $lock = $query ?
            $query->row()->lock :
            false;

        $query->free();

        $this->reset();

        return $lock;
    }

    public function releaseLock(string $name)
    {
        $query = $this
            ->select('RELEASE_LOCK(\''.md5($name).'\') AS lock')
            ->get();

        $lock = $query ?
            $query->row()->lock :
            false;

        $query->free();

        $this->reset();

        return $lock;
    }

}
