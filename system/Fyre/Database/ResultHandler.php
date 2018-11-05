<?php

namespace Fyre\Database;

abstract class ResultHandler
{
    protected $query;

    public function __construct(&$query)
    {
        $this->query =& $query;
    }

}
