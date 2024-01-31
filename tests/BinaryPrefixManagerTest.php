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
	public function testNormalizeHostInput(array $original, array $expected): void
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
}