<?php

namespace s9e\IPMatcherGenerator\Tests\AddressType;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use s9e\IPMatcherGenerator\AddressType\IPv4;

#[CoversClass('s9e\IPMatcherGenerator\AddressType\IPv4')]
class IPv4Test extends TestCase
{
	#[DataProvider('getExtractCidrBinaryPrefixTests')]
	public function testExtractCidrBinaryPrefix(string $cidr, string $expected): void
	{
		$this->assertEquals($expected, (new IPv4)->extractCidrBinaryPrefix($cidr));
	}

	public static function getExtractCidrBinaryPrefixTests(): array
	{
		return [
			[
				'127.0.0.1/32',
				'01111111000000000000000000000001'
			],
			[
				'127.0.0.1/8',
				'01111111'
			],
			[
				'127.0.0.1/12',
				'011111110000'
			],
		];
	}
}