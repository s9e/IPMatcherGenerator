<?php

namespace s9e\IPMatcherGenerator\Tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use s9e\IPMatcherGenerator\BinaryPrefixManager;

#[CoversClass('s9e\IPMatcherGenerator\BinaryPrefixManager')]
class BinaryPrefixManagerTest extends TestCase
{
	#[DataProvider('getOptimizeTests')]
	public function testOptimizeInput(array $original, array $expected): void
	{
		$this->assertEquals($expected, (new BinaryPrefixManager)->optimize($original));
	}

	public static function getOptimizeTests(): array
	{
		return [
			[
				[],
				[]
			],
			[
				[
					''
				],
				[
					''
				]
			],
			[
				[
					'1010',
					'1111'
				],
				[
					'1010',
					'1111'
				]
			],
			[
				[
					'100',
					'10010',
					'1111'
				],
				[
					'100',
					'1111'
				]
			],
			[
				[
					'100',
					'10010',
					'10011',
					'1111'
				],
				[
					'100',
					'1111'
				]
			],
		];
	}

	#[DataProvider('getPadTests')]
	public function testPadInput(array $prefixes, int $size, array $expected): void
	{
		$this->assertEquals($expected, (new BinaryPrefixManager)->pad($prefixes, $size));
	}

	public static function getPadTests(): array
	{
		return [
			[
				[
					'00000000',
					'11111111'
				],
				8,
				[
					'00000000',
					'11111111'
				]
			],
			[
				[
					'0000000',
					'1111111'
				],
				8,
				[
					'00000000',
					'00000001',
					'11111110',
					'11111111'
				]
			],
			[
				[
					'000000000000000',
					'000000001111111'
				],
				8,
				[
					'0000000000000000',
					'0000000000000001',
					'0000000011111110',
					'0000000011111111'
				]
			],
			[
				[
					'0000000'
				],
				16,
				array_map(fn($n) => sprintf('%016b', $n), range(0, 511))
			],
		];
	}
}