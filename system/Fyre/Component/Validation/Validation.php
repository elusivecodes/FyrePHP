<?php

namespace Fyre\Component\Validation;

use Closure;

use
    Config\Services;

use function
    array_key_exists,
    count,
    implode,
    is_array,
    is_callable,
    is_string,
    preg_match,
    preg_split;

class Validation implements ValidationInterface
{
    private $rules = [];
    private $errors = [];

    use Rules;

    public function __construct(?Ruleset $ruleSet = null)
    {
        Services::lang()->load('validation');

        if ($ruleSet) {
            $this->addRules($ruleSet->rules);
        }

        Services::logger()->debug('Validation class loaded');
    }

    public function errors(string $delimiter = '<br />'): string
    {
        return implode($delimiter, $this->errors);
    }

    public function setError(string $field, string $message): void
    {
        $this->errors[$field] = $message;
    }

    public function addRule(string $field, $rules, ?string $label = null, ?array $errors = null): void
    {
        if (is_string($rules)) {
            $rules = preg_split('/\|(?![^\[]*\])/', $rules);
        }

        if (empty($rules)) {
            return;
        }

        $this->rules[$field] = [
            'errors' => $errors,
            'field' => $field,
            'label' => $label ?? $field,
            'rules' => $rules
        ];
    }

    public function addRules(array $rules): void
    {
        foreach ($rules AS $rule) {
            if ( ! array_key_exists('field', $rule) || ! array_key_exists('rules', $rule)) {
                continue;
            }

            $this->addRule(
                $rule['field'],
                $rule['rules'],
                (array_key_exists('label', $rule) ?
                    $rule['label'] :
                    false
                ),
                (array_key_exists('errors', $rule) ?
                    $rule['error'] :
                    []
                )
            );
        }
    }

    public function validate(?array $validateData = null): bool
    {
        if ( ! $validateData) {
            $validateData = &$_POST;
        }

        if (empty($validateData)) {
            return false;
        }

        foreach ($this->rules AS $field => $fieldData) {
            $this->validateField(
                $fieldData,
                array_key_exists($field, $validateData) ?
                    $validateData[$field] :
                    null
            );
        }

        return ! count($this->errors);
    }

    protected function validateField(array $fieldData, $value = null, $index = 0): bool
    {
        if (is_array($value) && ! empty($value)) {
            foreach ($value AS $key => $val) {
                if ( ! $this->validateField($field, $val, $key)) {
                    return false;
                }
            }

            return true;
        }

        foreach ($fieldData['rules'] AS $rule) {
            if (is_callable($rule)) {
                if ( ! Closure::bind($rule, $this)($value, $fieldData)) {
                    return false;
                }

                continue;
            }

            $argument = null;
            if (preg_match('/^(.*?)\[(.*)\]$/', $rule, $match)) {
                $rule = $match[1];
                $argument = $match[2];
            }

            if ( ! $this->{$rule}($value, $argument)) {
                $this->setError(
                    $fieldData['field'],
                    Services::lang()->get(
                        'validation.'.$rule,
                        [
                            'field' => $fieldData['label'],
                            'param' => $argument
                        ]
                    )
                );

                return false;
            }
        }

        return true;
    }

}
