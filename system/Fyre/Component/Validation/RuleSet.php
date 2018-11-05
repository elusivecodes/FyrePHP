<?php

namespace Fyre\Component\Validation;

class RuleSet
{
    public $rules = [];

    public function __construct(?array $rules = null)
    {
        if ($rules) {
            $this->rules += $rules;
        }
    }

}
