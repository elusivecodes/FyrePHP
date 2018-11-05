<?php

namespace Frost;

class SetImmutable extends Set
{

    public function offsetSet($key, $value)
    {
        return false;
    }

    public function offsetUnset($key)
    {
        return false;
    }

    public function pushSet($array): self
    {
        return new SetImmutable($array);
    }

}
