<?php

namespace Fyre\Config;

abstract class BaseConfig
{

    public function __construct(array $config = [])
    {
        foreach($config AS $key => $val) {
            $this->$key = $val;
        }
    }

}
