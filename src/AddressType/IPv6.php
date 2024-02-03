<?php declare(strict_types=1);

/**
* @package   s9e\IPMatcherGenerator
* @copyright Copyright (c) The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\IPMatcherGenerator\AddressType;

use UnexpectedValueException;
use function array_map, array_slice, count, dechex, hexdec, implode, max, preg_match, preg_replace, sprintf, str_repeat, strlen, substr, substr_count;
use s9e\RegexpBuilder\Expression;

class IPv6 implements AddressTypeInterface
{
	public function extractCidrBinaryPrefix(string $cidr): string
	{
		$cidr = $this->expandCidrAddress($cidr);

		$regexp = '(^'
		        . '([1-9a-f](?:[0-9a-f](?:[0-9a-f][0-9a-f]?)?)?|0)'
		        . ':'
		        . '([1-9a-f](?:[0-9a-f](?:[0-9a-f][0-9a-f]?)?)?|0)'
		        . ':'
		        . '([1-9a-f](?:[0-9a-f](?:[0-9a-f][0-9a-f]?)?)?|0)'
		        . ':'
		        . '([1-9a-f](?:[0-9a-f](?:[0-9a-f][0-9a-f]?)?)?|0)'
		        . ':'
		        . '([1-9a-f](?:[0-9a-f](?:[0-9a-f][0-9a-f]?)?)?|0)'
		        . ':'
		        . '([1-9a-f](?:[0-9a-f](?:[0-9a-f][0-9a-f]?)?)?|0)'
		        . ':'
		        . '([1-9a-f](?:[0-9a-f](?:[0-9a-f][0-9a-f]?)?)?|0)'
		        . ':'
		        . '([1-9a-f](?:[0-9a-f](?:[0-9a-f][0-9a-f]?)?)?|0)'
		        . '/'
		        . '([2-9][0-9]?|0|1(?:[01][0-9]?|[3-9]|2[0-8]?)?)'
		        . '$)';
		if (!preg_match($regexp, $cidr, $m))
		{
			throw new UnexpectedValueException;
		}

		// Convert to 128 bits binary code
		$bits = sprintf('%016b%016b%016b%016b%016b%016b%016b%016b', ...array_map(hexdec(...), array_slice($m, 1, 8)));

		// Keep only the amount required for the CIDR prefix
		$bits = substr($bits, 0, (int) $m[9]);

		return $bits;
	}

	public function getAddressSize(): int
	{
		return 128;
	}

	public function getGroupSeparator(): string
	{
		 return ':';
	}

	public function getGroupSize(): int
	{
		return 16;
	}

	public function serializePrefix(array $values): array
	{
		$prefix = implode(':', array_map(dechex(...), $values));
		if (count($values) < 8)
		{
			$prefix .= ':';
		}
		$prefix = $this->compressPrefix($prefix);

		$prefix = [new Expression('^'), $prefix];
		if (count($values) >= 8)
		{
			$prefix[] = new Expression('$');
		}
		elseif (str_ends_with($prefix[1], '::') && end($values) === 0)
		{
			$maxCnt   = 8 - count($values);
			$prefix[] = new Expression('(?!' . str_repeat('[^:]*:', $maxCnt) . ')');
		}

		return $prefix;
	}

	protected function compressPrefix(string $prefix): string
	{
		if (!preg_match_all('((?::|\\b)0(?::0)++:?)', $prefix, $m, PREG_OFFSET_CAPTURE))
		{
			return $prefix;
		}

		$longestMatch = $m[0][0];
		foreach ($m[0] as $match)
		{
			if (strlen($match[0]) > strlen($longestMatch[0]))
			{
				$longestMatch = $match;
			}
		}

		return substr($prefix, 0, $longestMatch[1]) . '::' . substr($prefix, $longestMatch[1] + strlen($longestMatch[0]));
	}

	/**
	* Expand the address part of an IPv6 CIDR so it always have at least 8 groups
	*/
	protected function expandCidrAddress(string $cidr): string
	{
		if (!preg_match('((.*?)::(.*?)(/.*))s', $cidr, $m))
		{
			return $cidr;
		}

		$prefix = ($m[1] === '') ? '0' : $m[1];
		$suffix = ($m[2] === '') ? '0' : $m[2];
		$length = $m[3];

		$skippedCnt = max(1, 8 - substr_count($cidr, ':'));

		return $prefix . str_repeat(':0', $skippedCnt) . ':' . $suffix . $length;
	}
}