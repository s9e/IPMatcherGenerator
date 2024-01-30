<?php declare(strict_types=1);

/**
* @package   s9e\IPMatcherGenerator
* @copyright Copyright (c) The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\IPMatcherGenerator;

use UnexpectedValueException;
use function preg_match, sprintf, substr;

class Encoder
{
	public function encodeIPv4(string $cidr): string
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
		{
			throw new UnexpectedValueException;
		}

		// Convert to 32 bits binary code
		$bits = sprintf('%08b%08b%08b%08b', $m[1], $m[2], $m[3], $m[4]);

		// Keep only the amount required for the CIDR prefix
		$bits = substr($bits, 0, (int) $m[5]);

		return $bits;
	}

	public function encodeIPv6(string $cidr): string
	{
	}
}