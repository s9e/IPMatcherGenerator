<?php declare(strict_types=1);

/**
* @package   s9e\IPMatcherGenerator
* @copyright Copyright (c) The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\IPMatcherGenerator\NetworkSplitter;

interface NetworkSplitterInterface
{
	/**
	* @param  string $cidr
	* @param  int    $prefixLength
	* @return string[]
	*/
	public function splitIPv4(string $cidr, int $prefixLength): array;

	/**
	* @param  string $cidr
	* @param  int    $prefixLength
	* @return string[]
	*/
	public function splitIPv6(string $cidr, int $prefixLength): array;
}