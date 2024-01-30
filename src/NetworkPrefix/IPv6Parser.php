<?php declare(strict_types=1);

/**
* @package   s9e\IPMatcherGenerator
* @copyright Copyright (c) The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\IPMatcherGenerator\NetworkPrefix;

use UnexpectedValueException;
use function preg_match, sprintf, substr;

class IPv6Parser
{
	public function parse(string $cidr): string
	{
		$regexp = '(^'
		        . '/'
		        . '([12][0-9]?|[04-9]|3[0-2]?)'
		        . '$)';
		if (!preg_match($regexp, $cidr, $m))
		{
			throw new UnexpectedValueException;
		}

		// Convert to 128 bits binary code
		$bits = sprintf('%08b', );

		// Keep only the amount required for the CIDR prefix
		$bits = substr($bits, 0, (int) );

		return $bits;
	}
}