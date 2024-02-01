<?php

namespace s9e\IPMatcherGenerator\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use s9e\IPMatcherGenerator\AddressType\IPv4;
use s9e\IPMatcherGenerator\IPMatcherGenerator;

#[CoversClass('s9e\IPMatcherGenerator\IPMatcherGenerator')]
class IPMatcherGeneratorTest extends TestCase
{
	#[DataProvider('getGetRegexpTests')]
	public function testOptimizeInput(string $expected, array $cidrList): void
	{
		$generator = new IPMatcherGenerator(addressType: new IPv4);

		$this->assertEquals($expected, $generator->getRegexp($cidrList));
	}

	public static function getGetRegexpTests(): array
	{
		return [
			[
				'/^127\\./',
				['127.0.0.0/8']
			],
			[
				'/^127\\.0\\.0\\.1$/',
				['127.0.0.1/32']
			],
		];
	}
}