<?php

namespace Fyre\Component\Validation;

interface ValidationInterface
{

    // validation
    public function errors(): string;
    public function setError(string $field, string $message): void;
    public function addRule(string $field, $rules, ?string $label = null, ?array $errors = null): void;
    public function addRules(array $rules): void;
    public function validate(?array $validateData = null): bool;

    // rules
    public function required($value): bool;
    public function in($value, string $list): bool;
    public function matches($value, string $field): bool;
    public function differs($value, string $field): bool;
    public function regexMatch($value, string $regex): bool;
    public function minLength($value, string $min): bool;
    public function maxLength($value, string $max): bool;
    public function exactLength($value, string $length): bool;
    public function url($value): bool;
    public function email($value): bool;
    public function ip($value): bool;
    public function base64($value): bool;
    public function alpha($value): bool;
    public function alphaNumeric($value): bool;
    public function alphaSpaces($value): bool;
    public function alphaDash($value): bool;
    public function alphaDashSpaces($value): bool;
    public function number($value): bool;
    public function numberNotZero($value): bool;
    public function integer($value): bool;
    public function float($value): bool;
    public function greaterThan($value, string $min): bool;
    public function greaterOrEqual($value, string $min): bool;
    public function lessThan($value, string $max): bool;
    public function lessOrEqual($value, string $max): bool;

}
