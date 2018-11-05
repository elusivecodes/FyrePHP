<?php

namespace Fyre\Component\Parser;

trait Utility {

    public static $_chain_regex = '/\s+>>\s+/s';
    public static $_options_regex = '/\s*\/\/\s+/s';

    public $_strings = [];
    public $_functions = [];

	private $_if_count = 0;
	private $_switch_count = 0;
	private $_each_count = 0;
	private $_while_count = 0;
	private $_for_count = 0;

    public function stripFunctions(string &$template) {
        while (preg_match('/{{[^{}]*?}}/s', $template, $match, PREG_OFFSET_CAPTURE)) {
            $count = \count($this->_functions);
            $this->_functions[$count] = $match[0][0];
            $template = \substr_replace($template, '~~fyre/func['.$count.']~~', $match[0][1], \strlen($match[0][0]));
        }
    }

    public function stripStrings(string &$template) {
        $offset = 0;
        while (preg_match('/({{[^{}]*?)(["\'])(.*?)(?<!(?<!\\\)\\\)\2([^{}]*?}})/s', $template, $match, PREG_OFFSET_CAPTURE, $offset)) {
            $offset = $match[3][1];
            $count = \count($this->_strings);
            $this->_strings[$count] = \str_replace('\\'.$match[2][0], $match[2][0], $match[3][0]);
            $template = \substr_replace($template, '~~fyre/str['.$count.']~~', $match[2][1], \strlen($match[3][0]) + 2);
        }
    }

    public function injectFunctions(string &$template) {
        $offset = 0;
        while (preg_match('/~~fyre\/func\[(\d+)\]~~/', $template, $match, PREG_OFFSET_CAPTURE, $offset)) {
            $template = \substr_replace($template, $this->_functions[$match[1][0]], $match[0][1], \strlen($match[0][0]));
            $offset = $match[0][1] + \strlen($this->_functions[$match[1][0]]);
        }
    }

    public function injectStrings(string &$template) {
        $offset = 0;
        while (preg_match('/~~fyre\/str\[(\d+)\]~~/', $template, $match, PREG_OFFSET_CAPTURE, $offset)) {
            $template = \substr_replace($template, $this->_strings[$match[1][0]], $match[0][1], \strlen($match[0][0]));
            $offset = $match[0][1] + \strlen($this->_strings[$match[1][0]]);
        }
    }

    public function injectVars(string &$template) {
        $offset = 0;
        while (preg_match('/(?<!(?<!\\\)\\\)\$([^\s]+)/i', $template, $match, PREG_OFFSET_CAPTURE, $offset)) {
            $value = $this->data->data($match[1][0]);
            $template = \substr_replace($template, $value, $match[0][1], \strlen($match[0][0]));
            $offset = $match[0][1] + \strlen($value);
        }
    }

    public function parseGroups(string &$template) {
        $if_counts = [];
        $switch_counts = [];
        $each_counts = [];
        $while_counts = [];
        $for_counts = [];

        $scopes = [];

        $offset = 0;
        while (\preg_match(
            '/{{\s*(if|else if|switch|case|each|while|for)\s+(?=[^{}]*}})|{{\s*(else|\/if|default|\/switch|\/each|\/while|\/for)\s*}}|{{\s*(break)\s*(\d*)\s*}}/si',
            $template,
            $match,
            PREG_OFFSET_CAPTURE,
            $offset)) {
            $current = \array_shift($match);
            $match = \array_filter($match, function($value) {
                return !! $value[0];
            });
            $type = \strtolower(\array_shift($match)[0]);
            $scope = \end($scopes);

            $replace = '';
            switch ($type) {
                case 'if':
                    $if_counts[] = ++$this->_if_count;
                    $scopes[] = 'if';
                    $replace = '{{if['.$this->_if_count.'] ';
                    break;

                case 'else if':
                    if ($scope === 'if') {
                        $replace = '{{else if['.\end($if_counts).'] ';
                    }
                    break;

                case 'else':
                    if ($scope === 'if') {
                        $replace = '{{else['.\end($if_counts).']}}';
                    }
                    break;

                case '/if':
                    if ($scope === 'if') {
                        \array_pop($scopes);
                        $replace = '{{/if['.\array_pop($if_counts).']}}';
                    }
                    break;

                case 'switch':
                    $if_counts[] = ++$this->_if_count;
                    $scopes[] = 'if';
                    $replace = '{{if['.$this->_if_count.'] ';
                    break;

                case 'case':
                    if ($scope === 'switch' || $scope === 'case') {
                        $scopes[] = 'case';
                        $replace = '{{case['.\end($switch_counts).'] ';
                    }
                    break;

                case 'default':
                    if ($scope === 'switch' || $scope === 'case') {
                        $scopes[] = 'default';
                        $replace = '{{default['.\end($switch_counts).']}}';
                    }
                    break;

                case '/switch':
                    if ($scope === 'switch') {
                        \array_pop($scopes);
                        $replace = '{{/switch['.\array_pop($switch_counts).']}}';
                    }
                    break;

                case 'each':
                    $each_counts[] = ++$this->_each_count;
                    $scopes[] = 'each';
                    $replace = '{{each['.$this->_each_count.'] ';
                    break;

                case '/each':
                    if ($scope === 'each') {
                        \array_pop($scopes);
                        $replace = '{{/each['.\array_pop($each_counts).']}}';
                    }
                    break;

                case 'while':
                    $while_counts[] = ++$this->_while_count;
                    $scopes[] = 'while';
                    $replace = '{{while['.$this->_while_count.'] ';
                    break;

                case '/while':
                    if ($scope === 'while') {
                        \array_pop($scopes);
                        $replace = '{{/while['.\array_pop($while_counts).']}}';
                    }
                    break;

                case 'for':
                    $for_counts[] = ++$this->_for_count;
                    $scopes[] = 'for';
                    $replace = '{{for['.$this->_for_count.'] ';
                    break;

                case '/for':
                    if ($scope === 'for') {
                        \array_pop($scopes);
                        $replace = '{{/for['.\array_pop($for_counts).']}}';
                    }
                    break;

                case '/case':
                case '/default':
                    if ($scope === 'case' || $scope === 'default') {
                        $replace = '{{/'.$scope.'['.\end($switch_counts).']}}';
                    }
                    break;

                case 'break':
                    switch($scope) {
                        case 'case':
                        case 'default':
                            $replace = '{{/'.$scope.'['.\end($switch_counts).']}}';
                            break;

                        default:
                            $number = empty($match) ? '' : ' ' . \array_pop($match)[0];
                            $replace = '{{break '.$scope.$number.'}}';
                            break;
                    }
                    break;
            }

            $template = \substr_replace($template, $replace, $current[1], \strlen($current[0]));
            $offset = $current[1] + \strlen($replace);
        }
    }

}