<?php

namespace Frost;

class NumberImmutable extends Number
{

    public function pushNumber($number): self
    {
        return new NumberImmutable($number);
    }

}
