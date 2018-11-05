<?php

namespace Fyre\Database\Handlers;

use
    Fyre\Database\ResultHandler;

use const
    MYSQLI_ASSOC;

class MySQLiResult extends ResultHandler
{

    public function columns(): array
    {
        return $this->query->fetch_fields();
    }

    public function free()
    {
        return $this->query->free();
    }

    public function numColumns(): int
    {
        return $this->query->field_count;
    }

    public function numRows(): int
    {
        return $this->query->num_rows;
    }

    public function results(): array
    {
        $results = [];

        while ($row = $this->row()) {
            $results[] = $row;
        }

        return $results;
    }

    public function resultsArray(): array
    {
        return $this->query->fetch_all(MYSQLI_ASSOC);
    }

    public function row(): ?object
    {
        return $this->query->fetch_object();
    }

    public function rowArray(): array
    {
        return $this->query->fetch_array(MYSQLI_ASSOC);
    }

}
