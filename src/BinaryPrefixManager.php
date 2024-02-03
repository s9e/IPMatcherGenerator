<?php declare(strict_types=1);

/**
* @package   s9e\IPMatcherGenerator
* @copyright Copyright (c) The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\IPMatcherGenerator;

use const SORT_STRING, STR_PAD_LEFT;
use function array_values, count, decbin, range, sort, str_pad, str_starts_with, strlen;

class BinaryPrefixManager
{
	/**
	* @param  string[] $prefixes List of prefixes in binary form
	* @return string[]           Optimized list in lexicographical order
	*/
	public function optimize(array $prefixes): array
	{
		sort($prefixes, SORT_STRING);

		$max = count($prefixes) - 1;
		$i   = -1;
		while (++$i < $max)
		{
			if (!isset($prefixes[$i]))
			{
				continue;
			}

			$j = $i;
			while (++$j < $max && str_starts_with($prefixes[$j], $prefixes[$i]))
			{
				unset($prefixes[$j]);
			}
		}

		return array_values($prefixes);
	}

	/**
	* @param  string[] $prefixes List of prefixes in binary form
	* @param  int      $size
	* @return string[]           Expanded list, padded to a multiple of $size
	*/
	public function pad(array $prefixes, int $size): array
	{
		$paddedPrefixes = [];
		foreach ($prefixes as $prefix)
		{
			$extraLength = strlen($prefix) % $size;
			if ($extraLength === 0)
			{
				$paddedPrefixes[] = $prefix;
				continue;
			}

			// Fill the missing (least significant) bits with every possible combination
			$padSize = $size - $extraLength;
			foreach (range(0, (1 << $padSize) - 1) as $lsbValue)
			{
				$paddedPrefixes[] = $prefix . str_pad(decbin($lsbValue), $padSize, '0', STR_PAD_LEFT); 
			}
		}

		return $paddedPrefixes;
	}
}