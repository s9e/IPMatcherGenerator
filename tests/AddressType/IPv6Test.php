<?php

namespace s9e\IPMatcherGenerator\Tests\AddressType;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use s9e\IPMatcherGenerator\AddressType\IPv6;
use s9e\RegexpBuilder\Expression;

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
			[
				sprintf('%016b%016b', 0x2405, 0xB500) . str_repeat('0', 31),
				'2405:b500::/63'
			],
		];
	}

	#[DataProvider('getSerializePrefixTests')]
	public function testSerializePrefix(array $expected, array $values): void
	{
		$this->assertEquals($expected, (new IPv6)->serializePrefix($values));
	}

	public static function getSerializePrefixTests(): array
	{
		return [
			[
				[new Expression('^'), '0:1:2:3:4:5:6:7', new Expression('$')],
				[0, 1, 2, 3, 4, 5, 6, 7]
			],
			[
				[new Expression('^'), '0:1:2:3:4:5:6:'],
				[0, 1, 2, 3, 4, 5, 6]
			],
			[
				[new Expression('^'), '1234::abcd', new Expression('$')],
				[0x1234, 0, 0, 0, 0, 0, 0, 0xabcd]
			],
			[
				[new Expression('^'), '1234::abcd:0:0', new Expression('$')],
				[0x1234, 0, 0, 0, 0, 0xabcd, 0, 0]
			],
			[
				[new Expression('^'), '1234:'],
				[0x1234]
			],
			[
				[new Expression('^'), '1::1', new Expression('$')],
				[1, 0, 0, 0, 0, 0, 0, 1]
			],
			[
				[new Expression('^'), '::1', new Expression('$')],
				[0, 0, 0, 0, 0, 0, 0, 1]
			],
			[
				[new Expression('^'), '1::', new Expression('$')],
				[1, 0, 0, 0, 0, 0, 0, 0]
			],
			[
				[new Expression('^'), '1::', new Expression('(?![^:]*:)')],
				[1, 0, 0, 0, 0, 0, 0]
			],
			[
				[new Expression('^'), '1::', new Expression('(?![^:]*:[^:]*:[^:]*:[^:]*:)')],
				[1, 0, 0, 0]
			],
			[
				// https://datatracker.ietf.org/doc/html/rfc5952#section-4.2.3
				[new Expression('^'), '2001:0:0:1::1', new Expression('$')],
				[0x2001, 0, 0, 1, 0, 0, 0, 1]
			],
		];
	}
}