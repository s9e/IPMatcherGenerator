<?php declare(strict_types=1);

/**
* @package   s9e\IPMatcherGenerator
* @copyright Copyright (c) The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\IPMatcherGenerator\AddressType;

interface AddressTypeInterface
{
	/**
	* Extract and return given CIDR's prefix, in binary form
	*/
	public function extractCidrBinaryPrefix(string $cidr): string;

	/**
	* Return this address type's size in bits
	*/
	public function getAddressSize(): int;

	/**
	* Return this address type's group separator
	*/
	public function getGroupSeparator(): string;

	/**
	* Return this address type's group size in bits
	*/
	public function getGroupSize(): int;

	/**
	* @param  int[]  $values
	* @return string
	*/
	public function serializePrefix(array $values): string;
}