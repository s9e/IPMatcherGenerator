<?php declare(strict_types=1);

/**
* @package   s9e\IPMatcherGenerator
* @copyright Copyright (c) The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\IPMatcherGenerator\AddressType;

use UnexpectedValueException;
use function preg_match, sprintf, substr;

class IPv4 implements AddressTypeInterface
{
	public function extractCidrBinaryPrefix(string $cidr): string
	{
		// Quad-dotted notation of four decimal integers (0..255) followed by a slash and one
		// decimal integer (0..32)
		$regexp = '(^'
		        . '([3-9][0-9]?|0|1(?:[0-9][0-9]?)?|2(?:[0-4][0-9]?|[6-9]|5[0-5]?)?)'
		        . '\\.'
		        . '([3-9][0-9]?|0|1(?:[0-9][0-9]?)?|2(?:[0-4][0-9]?|[6-9]|5[0-5]?)?)'
		        . '\\.'
		        . '([3-9][0-9]?|0|1(?:[0-9][0-9]?)?|2(?:[0-4][0-9]?|[6-9]|5[0-5]?)?)'
		        . '\\.'
		        . '([3-9][0-9]?|0|1(?:[0-9][0-9]?)?|2(?:[0-4][0-9]?|[6-9]|5[0-5]?)?)'
		        . '/'
		        . '([12][0-9]?|[04-9]|3[0-2]?)'
		        . '$)';
		if (!preg_match($regexp, $cidr, $m))
		{var_dump($regexp,$cidr,$m);
			throw new UnexpectedValueException;
		}

		// Convert to 32 bits binary code
		$bits = sprintf('%08b%08b%08b%08b', $m[1], $m[2], $m[3], $m[4]);

		// Keep only the amount required for the CIDR prefix
		$bits = substr($bits, 0, (int) $m[5]);

		return $bits;
	}

	public function getAddressSize(): int
	{
		return 32;
	}

	public function getGroupSeparator(): string
	{
		 return '.';
	}

	public function getGroupSize(): int
	{
		return 8;
	}

	public function serializePrefix(array $values): string
	{
		$prefix  = '^' . implode('.', $values);
		$prefix .= (count($values) < 4) ? '.' : '$';

		return $prefix;
	}
}