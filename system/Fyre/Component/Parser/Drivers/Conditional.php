<?php

namespace Fyre\Component\Parser;

class Conditional extends \Fyre\Driver {

    public function switch($number, $string, $contents) {
        $value = $this->_parent->subparse($string);

        $offset = 0;
        while (\preg_match(
            '/({{(case|default)\['.$number.'\]\s*([^{}]*)}})(?:{{\2\['.$number.'\]\s*[^{}]*}})*(.*?){{/\2\['.$number.'\]\s*(\d*)\s*}}/s',
            $contents,
            $match,
            PREG_OFFSET_CAPTURE,
            $offset)) {
            if ($match[2][0] === 'default' || $this->evaluate($value.' == '.$match[3][0])) {
                return $this->_parent->reparse($match[4][0]);
            }

            $offset = $match[0][1] + \strlen($match[1][0]);
        }

        return '';
    }

    public function if($number, $string, $contents) {
        if ($this->evaluate($string)) {
            return $this->_parent->reparse(
                \preg_split('/{{else( if)?\['.$number.'\]\s*([^{}]*)}}/s', $contents, 2)[0]
            );
        }

        $offset = 0;
        while (\preg_match(
            '/^(.*?){{else( if)?\['.$number.'\]\s*([^{}]*)}}(.*?)(?:{{else( if)?\['.$number.'\]\s*([^{}]*)}}|$)/s',
            $contents,
            $match,
            PREG_OFFSET_CAPTURE,
            $offset)) {
            if ( ! $match[2][0] || $this->evaluate($match[3][0])) {
                return $this->_parent->reparse($match[4][0]);
            }

            $offset = $match[4][1] + \strlen($match[4][0]);
        }

        return '';
    }

	public function evaluate($string, $result = TRUE, &$conditionals = NULL) {
        if ( ! $conditionals) {
            $conditionals = [];
            while (\preg_match('/\(([^\(\)]+)\)/', $string, $match, PREG_OFFSET_CAPTURE)) {
                $count = \count($conditionals);
                $conditionals[$count] = $match[1][0];
                $string = \substr_replace($string, '~~fyre/conditional['.$count.']~~', $match[0][1], \strlen($match[0][0]));
            }
        }

		$mode = FALSE;
        $current = FALSE;
		foreach (preg_split('/\s*(&&|\|\|)\s*/i', $string, -1, PREG_SPLIT_DELIM_CAPTURE) AS $string) {
			if ($string === '&&') {
                if ( ! $current) {
                    break;
                }
                $mode = 'and';
			} else if ($string === '||') {
                $mode = 'or';
			} else if ($mode === 'or' AND $current) {
                continue;
            } else {
                if ( ! empty($conditionals)) {
                    \preg_match_all('/~~fyre\/conditional\[(\d+)\]~~/', $string, $matches, PREG_SET_ORDER);
                    if ( ! empty($matches)) {
                        foreach ($matches AS $match) {
                            $string = \str_replace($match[0], $conditionals[$match[1]], $string);
                            unset($conditionals[$match[1]]);
                        }
                        $current = $this->evaluate($string, $result, $conditionals);
                        continue;
                    }
                }

                $current = $this->checkCondition($string, $result);
            }
        }

        return $result === $current;
	}

	private function checkCondition($string, $result = TRUE) {

        if ( ! preg_match('/\s*(.*?)\s*(>=|<>|!==|===|<=|(?:>|!=|(?<!=)==|<)(?!=))\s*(.*)\s*/', $string, $match)) {
            $string = trim($this->_parent->subparse($string));

            if (substr($string, 0, 1) === '!') {
                $string = ltrim($string, '! ');
                $result = ! $result;
            }

            if (in_array($string, ['true', 'TRUE'])) {
                $string = TRUE;
            } else if (in_array($string, ['false', 'FALSE'])) {
                $string = FALSE;
            }

            return $string == $result;
        }

        $operator = $match[2];

		// parse a and b
		$a = trim($this->_parent->subparse($match[1]));
        $b = trim($this->_parent->subparse($match[3]));

		if (substr($a, 0, 1) === '!') {
            $a = ltrim($a, '! ');
            $result = ! $result;
        }

		if (in_array($operator, ['==', '===='])) {
            if (substr($b, 0, 1) === '!') {
                $b = ltrim($b, '! ');
                $result = ! $result;
            }
        }

		// convert true/false values to boolean
		if (in_array($a, ['true', 'TRUE'])) {
			$a = TRUE;
		} else if (in_array($a, ['false', 'FALSE'])) {
			$a = FALSE;
		}

		if (in_array($b, ['true', 'TRUE'])) {
            $b = TRUE;
		} else if (in_array($b, ['false', 'FALSE'])) {
			$b = FALSE;
		}

        // perform comparison
        switch ($operator) {
            case '>':
                return ($a > $b) === $result;
                break;

            case '>=':
                return ($a >= $b) === $result;
                break;

            case '<>':
                return ($a <> $b) === $result;
                break;

            case '<=':
                return ($a <= $b) === $result;
                break;

            case '<':
                return ($a < $b) === $result;
                break;

            case '==':
                return ($a == $b) === $result;
                break;

            case '===':
                return ($a === $b) === $result;
                break;

            case '!=':
                return ($a != $b) === $result;
                break;

            case '!==':
                return ($a !== $b) === $result;
                break;

        }
	}

}