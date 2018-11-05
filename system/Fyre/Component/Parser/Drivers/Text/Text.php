<?php

namespace Fyre\Component\Parser;

utility('string');

class Text extends \Fyre\Driver {

    public function parse($string, $input = FALSE) {
        return $input ? $input : $this->_parent->subparse($string);
    }

    public function chars($string, $input) {
        return \strlen(
            $this->parse($string, $input)
        );
    }

    public function compare($string, $input) {
        \similar_text($string, $input, $result);
        return $result;
    }

    public function levenshtein($string, $input) {
        return \levenshtein($string, $input);
    }

    public function lower($string, $input) {
		return \strtolower(
            $this->parse($string, $input)
        );
    }

    public function metaphone($string, $input) {
        return \metaphone(
            $this->parse($string, $input)
        );
    }

    public function random($string, $input, $options) {
        return \str_random($options['length']);
    }

    public function repeat($string, $input, $options) {
        if ( ! isset($options['number'])) {
            $options['number'] = 1;
        }

        return \str_repeat(
            $string,
            (int) $options['number']
        );
    }

    public function replace($string, $input, $options) {
        if ( ! isset($options['search']) OR ! isset($options['replace'])) {
            return $string;
        }

        if (isset($options['sensitive'])) {
            return str_replace($options['search'], $options['replace'], $string);
        }

        return str_ireplace($options['search'], $options['replace'], $string);
    }

    public function shuffle($string, $input) {
        return \str_shuffle(
            $this->parse($string, $input)
        );
    }

    public function soundex($string, $input) {
        return \soundex(
            $this->parse($string, $input)
        );
    }

    public function split($string, $input, $options) {
        isset($options['start']) || $options['start'] = 0;

        return isset($options['length']) ?
            \substr(
                $this->parse($string, $input),
                (int) $options['start'],
                (int) $options['length']
            ) :
            \substr(
                $this->parse($string, $input),
                (int) $options['start']
            );
    }

    public function title($string, $input) {
        return \str_title(
            $this->parse($string, $input)
        );
    }

    public function upper($string, $input) {
		return \strtoupper(
            $this->parse($string, $input)
        );
    }

    public function words($string, $input) {
        return \str_word_count(
            $this->parse($string, $input)
        );
    }

}