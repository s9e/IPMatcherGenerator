<?php declare(strict_types=1);

/**
* @package   s9e\IPMatcherGenerator
* @copyright Copyright (c) The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\IPMatcherGenerator\NetworkSplitter;

use IPTools\Network;

class S1lentiumIPTools implements NetworkSplitterInterface
{
	public function splitIPv4(string $cidr, int $prefixLength): array
	{
		return $this->split($cidr, $prefixLength);
	}

	public function splitIPv6(string $cidr, int $prefixLength): array
	{
		return $this->split($cidr, $prefixLength);
	}

	protected function split(string $cidr, int $prefixLength): array
	{
		$subnets = [];
		foreach (Network::parse($cidr)->moveTo($prefixLength) as $subnet)
		{
			$subnets[] = (string) $subnet;
		}

		return $subnets;
	}
}