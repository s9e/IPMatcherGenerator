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
		public ?Builder                  $regexpBuilder,
		public ?NetworkSplitterInterface $networkSplitter
	)
	{
	}

	/**
	* @param  string[] $cidrList
	* @return string
	*/
	public function getIPv4Regexp(array $cidrList): string
	{
		$this->init();

		$masks = [];
		foreach ($cidrList as $cidr)
		{
			foreach ($this->networkSplitter->splitIPv4($cidr) as $subnet)
			{
				$mask = 
			}
		}
	}

	/**
	* @param  string[] $cidrList
	* @return string
	*/
	public function getIPv6Regexp(array $cidrList): string
	{
		$this->init();
	}

	public function init(): void
	{
		if (!isset($this->networkSplitter))
		{
			$this->networkSplitter = new S1lentiumIPTools;
		}
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