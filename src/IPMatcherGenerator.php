<?php declare(strict_types=1);

/**
* @package   s9e\IPMatcherGenerator
* @copyright Copyright (c) The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\IPMatcherGenerator;

use s9e\IPMatcherGenerator\AddressType\AddressTypeInterface;
use s9e\RegexpBuilder\Builder;
use s9e\RegexpBuilder\Factory\PHP;

class IPMatcherGenerator
{
	public function __construct(
		public  AddressTypeInterface $addressType,
		public  BinaryPrefixManager  $binaryPrefixManager = new BinaryPrefixManager,
		public ?Builder              $regexpBuilder       = null
	)
	{
	}

	/**
	* @param  string[] $cidrList
	* @return string
	*/
	public function getRegexp(array $cidrList): string
	{
		$this->init();

		// Prepare the list of prefixes in binary form
		$binaryPrefixes = [];
		foreach ($cidrList as $cidr)
		{
			$binaryPrefixes[] = $this->addressType->extractCidrBinaryPrefix($cidr);
		}

		$addressSize = $this->addressType->getAddressSize();
		$groupSize   = $this->addressType->getGroupSize();

		$binaryPrefixes = $this->binaryPrefixManager->optimize($binaryPrefixes);
		$binaryPrefixes = $this->binaryPrefixManager->pad($binaryPrefixes, $groupSize);

		// Serialize the prefixes to the address format and add the start/end assertions as needed
		$strings = [];
		foreach ($binaryPrefixes as $binaryPrefix)
		{
			$values    = array_map(bindec(...), str_split($binaryPrefix, $groupSize));
			$strings[] = $this->addressType->serializePrefix($values);
		}

		// Build the final regexp
		$regexp = '/' . $this->regexpBuilder->build($strings) . '/';

		return $regexp;
	}

	public function init(): void
	{
		if (!isset($this->regexpBuilder))
		{
			$this->regexpBuilder = PHP::getBuilder(delimiter: '/');
			$this->regexpBuilder->meta->set('^', '^');
			$this->regexpBuilder->meta->set('$', '$');

			// 8 bit number in decimal
			$this->regexpBuilder->meta->set('(?&d8)', '[3-9][0-9]?|0|1([0-9][0-9]?)?|2([0-4][0-9]?|[6-9]|5[0-5]?)?');

			// 16 bit number in hexadecimal
			$this->regexpBuilder->meta->set('(?&h16)', '[1-9a-f]([0-9a-f]([0-9a-f][0-9a-f]?)?)?|0');
		}
	}
}