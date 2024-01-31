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
	public function testExtractCidrBinaryPrefix(string $expected, string $cidr): void
	{
		$this->assertEquals($expected, (new IPv4)->extractCidrBinaryPrefix($cidr));
	}

	public static function getExtractCidrBinaryPrefixTests(): array
	{
		return [
			[
				'01111111000000000000000000000001',
				'127.0.0.1/32'
			],
			[
				'01111111',
				'127.0.0.1/8'
			],
			[
				'011111110000',
				'127.0.0.1/12'
			],
			[
				'00000001000000100000001100000100',
				'1.2.3.4/32'
			],
		];
	}

	#[DataProvider('getSerializePrefixTests')]
	public function testSerializePrefix(string $expected, array $values): void
	{
		$this->assertEquals($expected, (new IPv4)->serializePrefix($values));
	}

	public static function getSerializePrefixTests(): array
	{
		return [
			[
				'127.0.0',
				[127, 0, 0]
			],
		];
	}
}