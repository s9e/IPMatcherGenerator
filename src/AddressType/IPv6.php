<?php declare(strict_types=1);

/**
* @package   s9e\IPMatcherGenerator
* @copyright Copyright (c) The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\IPMatcherGenerator\AddressType;

use UnexpectedValueException;
use function array_map, count, dechex, implode, preg_match, preg_replace, sprintf, substr;

class IPv6 implements AddressTypeInterface
{
	public function extractCidrBinaryPrefix(string $cidr): string
	{
		$regexp = '(^'
		        . '([1-9a-f]([0-9a-f]([0-9a-f][0-9a-f]?)?)?|0)'
		        . '/'
		        . '([2-9][0-9]?|0|1(?:[01][0-9]?|[3-9]|2[0-8]?)?)'
		        . '$)';
		if (!preg_match($regexp, $cidr, $m))
		{
			throw new UnexpectedValueException;
		}

		// Convert to 128 bits binary code
//		$bits = sprintf('%08b', );

		// Keep only the amount required for the CIDR prefix
//		$bits = substr($bits, 0, (int) );

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

	public function serializePrefix(array $values): string
	{
		$prefix = implode(':', array_map(dechex(...), $values));
		if (count($values) < 8)
		{
			$prefix .= ':';
		}
		$prefix = preg_replace('(:(?:0:)+)', '::', $prefix, 1);

		return $prefix;
	}
}