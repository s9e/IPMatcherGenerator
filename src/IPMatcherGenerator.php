<?php declare(strict_types=1);

/**
* @package   s9e\IPMatcherGenerator
* @copyright Copyright (c) The s9e authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\IPMatcherGenerator;

use s9e\RegexpBuilder\Builder;
use s9e\RegexpBuilder\Factory\PHP;
use s9e\IPMatcherGenerator\NetworkSplitter\NetworkSplitterInterface;
use s9e\IPMatcherGenerator\NetworkSplitter\S1lentiumIPTools;

class IPMatcherGenerator
{
	public function __construct(
		public AddressTypeInterface $addressType,
		public Builder              $regexpBuilder   = null,
		public PrefixOptimizer      $prefixOptimizer = new PrefixOptimizer
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

		$binaryPrefixes = [];
		foreach ($cidrList as $cidr)
		{
			$binaryPrefixes[] = $this->addressType->extractCidrBinaryPrefix($cidr);
		}

		$binaryPrefixes = $this->prefixOptimizer->optimize($binaryPrefixes);
		$binaryPrefixes = $this->
	}

	public function init(): void
	{
		if (!isset($this->regexpBuilder))
		{
			$this->regexpBuilder = s9e\RegexpBuilder\PHP::getBuilder(delimiter: '/');
			$this->regexpBuilder->meta->set('^', '^');
			$this->regexpBuilder->meta->set('$', '$');

			// 8 bit number in decimal
			$this->regexpBuilder->meta->set('(?&d8)', '[3-9][0-9]?|0|1([0-9][0-9]?)?|2([0-4][0-9]?|[6-9]|5[0-5]?)?');

			// 16 bit number in hexadecimal
			$this->regexpBuilder->meta->set('(?&h16)', '[1-9a-f]([0-9a-f]([0-9a-f][0-9a-f]?)?)?|0');
		}
	}
}