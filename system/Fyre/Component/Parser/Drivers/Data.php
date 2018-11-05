<?php

namespace Fyre\Component\Parser;

utility('array');

class Data extends \Fyre\Driver {

    public function alias($string, $input = FALSE) {
        $key = $this->get_key($string);
        $string = $this->clean_string($string, $key);
        $alias = $input !== FALSE ? $input : $this->get_key($string);

        \data_set($this->_parent->_data, $alias, \data_get($this->_parent->_data, $key));
    }

    public function data($string, $input = FALSE) {
        $key = $this->get_key($string);
        $value = FALSE;
        if ($input !== FALSE) {
            $value = $input;
        } else {
            $string = $this->clean_string($string, $key);
            if ($string) {
                $string = $this->_parent->subparse($string);
                $value = $string;
            }
        }

        if ($value === FALSE) {
            return \data_get($this->_parent->_data, $key);
        }

        \data_set($this->_parent->_data, $key, $value);
    }

    public function session($string, $input = FALSE) {
        $key = $this->get_key($string);
        $value = $input !== FALSE ? $input : $this->clean_string($string, $key);

        if ($value === FALSE) {
            return \data_get($_SESSION, $key);
        }

        \data_set($_SESSION, $key, $value);
    }

    private function clean_string($string, $key) {
        return \substr($string, \strlen($key) + 1);
    }

    private function get_key($string) {
        return \strtok($string, ' ');
    }

}