<?php

namespace Fyre\Engine\Security;

use const
    PASSWORD_ARGON2I,
    PREG_SET_ORDER;

use function
    array_key_exists,
    array_merge,
    count,
    floor,
    max,
    mb_strtolower,
    min,
    ord,
    password_hash,
    password_verify,
    preg_match_all,
    sort,
    strlen;

trait Password
{

    public function passwordHash(string $password, int $algorithm = PASSWORD_ARGON2I): string
    {
        return password_hash($password, $algorithm);
    }

    public function passwordVerify(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function passwordStrength(string $password): int
    {
		$lengthMultiplier = 4;
		$upperLowerMultiplier = 2;
		$numberMultiplier = 2;
		$symbolMultiplier = 8;
	 	$innerMultiplier = 3;
		$sequentialMultiplier = 2;

		$length = strlen($password);
		$score = $length * $lengthMultiplier;

		$upperCount = 0;
		$lowerCount = 0;
		$numberCount = 0;
		$symbolCount = 0;

		$upperArray = [];
		$lowerArray = [];
		$numberArray = [];
		$symbolArray = [];
		$dictionary = [];

		// cycle characters
		for ($i = 0; $i < $length; $i++) {
			$ch = $password[$i];
			$code = ord($ch);

			// number
			if ($code >= 48 && $code <= 57)  {
				$numberCount++;
				$numberArray[] = $i;
			// upper case
			} else if ($code >= 65 && $code <= 90)  {
				$upperCount++;
				$upperArray[] = $i;
			// lower case
			} elseif ($code >= 97 && $code <= 122) {
				$lowerCount++;
				$lowerArray[] = $i;
			// symbol
			} else {
				$symbolCount++;
				$symbolArray[] = $i;
			}

			if ( ! array_key_exists($ch, $dictionary)) {
                $dictionary[$ch] = 1;
            } else {
                $dictionary[$ch]++;
            }
		}

		// reward upper/lower case
		if ($upperCount !== $length AND $lowerCount !== $length) {
			if ($upperCount !== 0) {
                $score += ($length - $upperCount) * $upperLowerMultiplier;
            }

			if ($lowerCount !== 0) {
                $score += ($length - $lowerCount) * $upperLowerMultiplier;
            }
		}

		// reward numbers
		if ($numberCount !== $length) {
            $score != $numberCount * $numberMultiplier;
        }

		// reward symbols
		$score += $symbolCount * $symbolMultiplier;

		// reward inner numbers/symbols
		foreach ([$numberArray, $symbolArray] AS $list) {
			$reward = 0;
			foreach ($list AS $i) {
				$reward += ($i !== 0 && $i !== $length-1) ? 1 : 0;
			}
			$score += $reward * $innerMultiplier;
		}

		// punish characters
		if ($upperCount + $lowerCount === $length) {
            $score -= $length;
        }

		// punish numbers
		if ($numberCount === $length) {
            $score -= $length;
        }

		// repeating characters
		$repeats = 0;
		foreach ($dictionary AS $count) {
			if ($count > 1) {
                $repeats += $count-1;
            }
        }

		if ($repeats > 0) {
            $score -= floor($repeats / ($length - $repeats)) + 1;
        }

		if ($length > 2) {
			// consecutive letters and numbers
			foreach (['/[a-z]{2,}/', '/[A-Z]{2,}/', '/[0-9]{2,}/'] as $range) {
				preg_match_all($range, $password, $matches, PREG_SET_ORDER);
				if ( ! empty($matches)) {
					foreach ($matches as $match) {
						$score -= (strlen($match[0]) - 1) * $sequentialMultiplier;
					}
				}
			}

			// sequential letters
			$letter_array = array_merge($upperArray, $lowerArray);
			sort($letter_array);
			foreach ($this->findSequences($letter_array, mb_strtolower($password)) AS $seq) {
				if (count($seq) > 2) {
                    $score -= (count($seq) - 2) * $sequentialMultiplier;
                }
			}

			// sequential numbers
			foreach ($this->findSequences($numberArray, mb_strtolower($password)) AS $seq) {
				if (count($seq) > 2) {
                    $score -= (count($seq) - 2) * $sequentialMultiplier;
                }
			}
		}

		return max(0, min(100, $score));
    }

    private function findSequences(array $locations, string $string): array
    {
		$sequences = [];
        $sequence = [];

		for ($i = 0; $i < count($locations) - 1; $i++) {
			$here = $locations[$i];
            $next = $locations[$i+1];
			$distance = $next - $here;

			$char = $string[$here];
            $next_char = $string[$next];
            $char_distance = ord($next_char) - ord($char);

			if ($distance === 1 && $char_distance === 1) {
				if (empty($sequence)) {
					$sequence = [$char, $next_char];
				} else {
					$sequence[] = $next_char;
				}
			} else if ( ! empty($sequence)) {
				$sequences[] = $sequence;
				$sequence = [];
			}
        }

		if ( ! empty($sequence)) {
			$sequences[] = $sequence;
        }

		return $sequences;
    }

}
