<?php

namespace s9e\IPMatcherGenerator\Tests\AddressType;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use s9e\IPMatcherGenerator\AddressType\IPv6;

#[CoversClass('s9e\IPMatcherGenerator\AddressType\IPv6')]
class IPv6Test extends TestCase
{
	#[DataProvider('getExtractCidrBinaryPrefixTests')]
	public function testExtractCidrBinaryPrefix(string $expected, string $cidr): void
	{
		$this->assertEquals($expected, (new IPv6)->extractCidrBinaryPrefix($cidr));
	}

	public static function getExtractCidrBinaryPrefixTests(): array
	{
		return [
			[
				sprintf('%016b%016b', 0x2405, 0xB500),
				'2405:b500::/32'
			],
		];
	}

	#[DataProvider('getSerializePrefixTests')]
	public function testSerializePrefix(string $expected, array $values): void
	{
		$this->assertEquals($expected, (new IPv6)->serializePrefix($values));
	}

	public static function getSerializePrefixTests(): array
	{
		return [
			[
				'0:1:2:3:4:5:6:7',
				[0, 1, 2, 3, 4, 5, 6, 7]
			],
			[
				'0:1:2:3:4:5:6:',
				[0, 1, 2, 3, 4, 5, 6]
			],
			[
				'1234::abcd',
				[0x1234, 0, 0, 0, 0, 0, 0, 0xabcd]
			],
			[
				'1234::abcd:0:0:0:0',
				[0x1234, 0, 0, 0xabcd, 0, 0, 0, 0]
			],
			[
				'1234::',
				[0x1234, 0]
			],
		];
	}
}