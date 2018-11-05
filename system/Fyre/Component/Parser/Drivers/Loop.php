<?php

namespace Fyre\Component\Parser;

class Loop extends \Fyre\Driver {

    public function each($string, $contents) {

        $as_split = preg_split('/\s*>>\s*/s', $string, 2);
        $array = $this->_parent->data->data($as_split[0]);

        $replace = '';
        if (is_array($array)) {

            $key_value_split = preg_split('/\s*=>\s*/s', $as_split[1], 2);
            if (isset($key_value_split[1])) {
                $index = $key_value_split[0];
                $key = $key_value_split[1];
            } else {
                $index = FALSE;
                $key = $key_value_split[0];
            }

            $break = $this->_parent->_break;
            $this->_parent->_loops[] = 'each';

            foreach ($array AS $i => $value) {
                if ($index) {
                    $this->_parent->data->data($index, $i);
                } else if (is_array($value)) {
                    $value['i'] = $i;
                }

                $this->_parent->data->data($key, $value);

                $replace .= $this->_parent->reparse($contents);

                if ($this->_parent->_break > $break) {
                    $this->_parent->_break--;
                    break;
                }
            }

            array_pop($this->_parent->_loops);
        }

        return $replace;
    }

    public function for($string, $contents) {
        $segment_split = preg_split('/\s*\/\/\s*/s', $string, 2);

        $key_value = $segment_split[0];
        $key = $this->_parent->data->get_key($key_value);
        $value = $this->_parent->data->clean_string($key_value, $key);

        $value = $this->_parent->subparse($value);
        $this->_parent->data->data($key, $value);

        $chain_split = preg_split('/\s*>>\s*/s', $segment_split[1]);
        $condition = array_shift($chain_split);

        $chains = [];
        foreach ($chain_split AS $chain) {
            $chains[] = $this->_parent->parseChain($chain);
        }

        $break = $this->_parent->_break;
        $this->_parent->_loops[] = 'for';

        $replace = '';
        while ($this->_parent->conditional->_full_check($condition)) {
            $replace .= $this->_parent->reparse($contents);

            if ($this->_parent->_break > $break) {
                $this->_parent->_break--;
                break;
            }

            foreach ($chains AS $chain) {
                $value = $this->_parent->_chain($chain, $value);
            }
            $this->_parent->data->data($key, $value);
        }

        array_pop($this->_parent->_loops);

        return $replace;
    }

    public function while($string, $contents) {
        $break = $this->_parent->_break;
        $this->_parent->_loops[] = 'while';

        $replace = '';
        while ($this->_parent->conditional->_full_check($string)) {
            $replace .= $this->_parent->reparse($contents);

            if ($this->_parent->_break > $break) {
                $this->_parent->_break--;
                break;
            }
        }

        array_pop($this->_parent->_loops);

        return $replace;
    }

}