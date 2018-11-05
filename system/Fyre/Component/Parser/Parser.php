<?php

namespace Fyre\Component;

class Parser extends \Fyre\Driver_lib {
    public $_data = [];

    public $_exit = false;
    public $_break = false;

    public function parse(string $template, array $data = []) {
        $this->_data = $data;

        return $this->reparse($template, TRUE);
    }

    public function subparse(string $template) {
        $this->injectVars($template);
        $template = $this->reparse($template);
        $this->injectStrings($template);

        return $template;
    }

    public function reparse(string $template, bool $newparse = FALSE) {
        if ($newparse) {
            $this->stripStrings($template);
            $this->stripFunctions($template);
        }

        $this->injectFunctions($template);

        if ($newparse) {
            $this->parseGroups($template);
        }

        $output = '';
        $offset = 0;
        while (\preg_match(
            '/{{([a-z]+)\[(\d+)\]\s*([^{}]*)}}(.*){{\/\1\[\2\]}}|{{([^{}]+)}}/si',
            $template,
            $match,
            PREG_OFFSET_CAPTURE,
            $offset
        )) {
            $output .= substr($template, $offset, $match[0][1] - $offset);
            $offset = $match[0][1] + \strlen($match[0][0]);

            // group match
            if ($match[1][0]) {
                $function = \strtolower($match[1][0]);

                switch ($function) {
                    case 'if':
                    case 'switch':
                        $output .= $this->conditional->{$function}($match[2][0], $match[3][0], $match[4][0]);
                        break;

                    case 'each':
                    case 'while':
                    case 'for':
                        $output .= $this->loop->{$function}($match[3][0], $match[4][0]);
                        break;
                }

            // function match
            } else if ($match[5][0]) {
                $string = \trim($match[5][0]);

                // check for chained methods
                $chains = \preg_split(static::$_chain_regex, $string);
                $first = \array_shift($chains);
                $data = $first['driver'] === 'data' && $first['method'] === 'data';

                // parse first function
                $value = $this->chain($this->parseChain($first));

                // follow the chain
                foreach ($chains AS $chain) {
                    $value = $this->chain($this->parseChain($chain), $value);
                }

                if ($value) {
                    $output .= $this->reparse($value, $data);
                }    
            }
        }

        return $output;
    }

    public function chain(array $data, $input = FALSE) {
        return $data['driver'] ?
            $this->subparse($data['string']) :
            $this->{$data['driver']}->{$data['method']}(
                $data['string'],
                $input,
                \array_map([$this, 'subparse'], $data['options'])
            );
    }

    public function parseChain(string $string) {
        $options_split = \preg_split(static::$_options_regex, $string);
        $string = \array_shift($options_split);
        $string = \trim($string);

        if (\substr($string, 0, 1) === '$') {
            $driver = 'data';
            $method = 'data';
            $string = \substr($string, 1);
        } else {
            $token = \strtok($string, ' ');
            $token_split = \preg_split('/\//', $function, 2);
            $driver = \array_shift($token_split);
            $method = ! empty($token_split) ? \array_shift($token_split) : $driver;

            if ( ! $this->$driver || ! method_exists($this->$driver, $method)) {
                $driver = FALSE;
                $method = FALSE;
            } else {
                $string = \substr($string, \strlen($token) + 1);
            }    
        }

        $options = [];
        if ( ! empty($options_split)) {
            \parse_str(\array_shift($options_split), $options);
        }

        return [
            'driver' => $driver,
            'method' => $method,
            'string' => \trim($string),
            'options' => $options
        ];
    }

    use Parser\Utility;

}